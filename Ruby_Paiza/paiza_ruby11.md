

あなたはお祭りで金魚すくいをすることにしました。 そこのお店では耐久値が x のポイがあります。 
ポイは重量 w の金魚をすくったとき耐久値が w 減り、耐久値が 0 になった瞬間壊れます。ポイが壊れた場合、そのときの金魚はすくうことができません。

あなたは N 個のポイを順に使って 1 番目の金魚から M 番目までを順番にすくうことにしました。 
すべてのポイが壊れるまでにすくうことのできる金魚の数を答えるプログラムを作成してください。

入力例 1 では 1 つ目のポイで 1 匹目をすくい耐久値が 5 になります。
2 匹目をすくおうとすると耐久値が 0 になるためポイが壊れます。同様にして 2 つ目のポイで金魚をすくっていき、最終的に以下の図のように 4 匹の金魚をすくうことができます。

```ruby
m, n, x = gets.split(" ").map(&:to_i)
w = m.times.map { gets.to_i }
count = 0
x_initial = x

(1..m).each do |fish|
    
    fish -= 1
    
    if x = x - w[fish] == 0
        n -= 1
        count += 1
        break if n == 0
        
        if x == 0
            n -= 1
            x = x_initial
            break if n == 0
        end
        
    elsif x < w[fish]
        n -= 1
        break if n == 0
        
        if x == 0
            n -= 1
            x = x_initial
            break if n == 0
        end
        
    elsif x - w[fish] >= w[fish]
        count += 1
        x = x - w[fish]
    end
end

puts count
```

## 最初のコードの何が間違っていたか

---

### 間違い① `if x = x - w[fish] == 0`（演算子の優先順位）

```ruby
if x = x - w[fish] == 0   # 意図：x を更新して 0 かどうか確認したい
```

Ruby は `==` を `=` より先に評価する。なので実際の動きはこうなる：

```ruby
if x = (x - w[fish] == 0)   # x - w[fish] == 0 の true/false が x に代入される
```

x に数値ではなく `true` か `false` が入ってしまう。
以降の `x < w[fish]` などの比較がすべて壊れる。

---

### 間違い② ポイが壊れたのに `count += 1` している

```ruby
if x - w[fish] == 0
    n -= 1
    count += 1    # ← ここ
```

問題文：「耐久値が 0 になった瞬間壊れます。ポイが壊れた場合、そのときの金魚はすくうことができません」

x - w[fish] == 0 = 耐久値が 0 になる = ポイが壊れる = **魚はすくえない**。
count してはいけない。

---

### 間違い③ `if x == 0` の中の x がまだ更新されていない

```ruby
if x - w[fish] == 0       # x はまだ元の値のまま（例: x = 5）
    n -= 1
    count += 1
    break if n == 0

    if x == 0             # x は 5 のまま → 絶対に入らない
        x = x_initial
    end
```

x を更新する代入（`x = x - w[fish]`）を一度もしていないので、
`if x == 0` をチェックしても x はずっと元の値のまま。
このリセット処理は永遠に実行されない。

---

### 間違い④ `elsif x - w[fish] >= w[fish]` の条件が狭すぎる

```ruby
elsif x - w[fish] >= w[fish]   # これは x >= 2 * w[fish] と同じ意味
```

例えば x = 7、w = 5 のとき：
- x - w[fish] = 2
- 2 >= 5 は **false**
- どの条件にも入らず何も起きない（魚がスキップされる）

魚をすくえる条件は `x > w[fish]` だけでよい。
`x >= 2 * w[fish]` という制約は問題文に存在しない。

---

### 正しいコード（全部まとめると）

```ruby
(0...m).each do |fish|
    if x > w[fish]       # すくえる（耐久値が残る）
        count += 1
        x -= w[fish]
    else                 # x <= w[fish] → ポイが壊れる → 魚はすくえない
        n -= 1
        x = x_initial
        break if n == 0
    end
end
```

条件は「引く前に確認」→「すくえるときだけ引く」の順番が正しい。


---
---
---
---
---
---
---

# 解答

```ruby
m, n, x = gets.split(" ").map(&:to_i)
w = m.times.map { gets.to_i }
count = 0
x_initial = x

fish = 0

while n > 0 && fish < m

    if x <= w[fish]
        n -= 1
        x = x_initial
    elsif x > w[fish]
        count += 1
        x -= w[fish]
        fish += 1
    end
end

puts count
```

## コード解説

### while の終了条件

```ruby
while n > 0 && fish < m
```

- `n > 0`：ポイが残っている間だけ回す
- `fish < m`：魚を全部見終わったら止まる
- どちらか片方でも false になったらループ終了

---

### ポイが壊れるとき

```ruby
if x <= w[fish]
    n -= 1        # ポイを1本消費
    x = x_initial # 新しいポイにリセット
                  # fish += 1 を書かない → 同じ魚に留まる
```

`x <= w[fish]` = 引いたら耐久値が 0 以下になる = ポイが壊れる。
魚はすくえないので count しない。
`fish` を増やさないことで、次のループでも同じ魚を試みる。

---

### すくえるとき

```ruby
elsif x > w[fish]
    count += 1    # 魚を1匹カウント
    x -= w[fish]  # 耐久値を減らす
    fish += 1     # 次の魚へ進む
```

`x > w[fish]` = 引いても耐久値が残る = すくえる。
`fish += 1` で次の魚に進む。

## each と while の違い

### each はループ変数を自分で動かせない

```ruby
(0...3).each do |fish|
    # fish は 0 → 1 → 2 と Ruby が自動で進める
    # fish を止める・戻す・スキップする手段がない
end
```

each は「配列や範囲の要素を順番に全部処理する」ためのもの。
「同じ番号をもう一度」という動作は構造上できない。

---

### while は自分でインデックスを管理する

```ruby
fish = 0
while fish < m
    if すくえる
        fish += 1   # 自分で増やす → 次の魚へ進む
    else
        # fish += 1 を書かない → 同じ魚に留まる
    end
end
```

`fish += 1` をどこに書くかを自分で決められるので、
「条件によって次に進む / 留まる」を制御できる。

---

### この問題で each が使えない理由

ポイが壊れたとき、同じ魚を次のポイで再挑戦する必要がある。
each だとポイが壊れても必ず次の魚に進んでしまうので、
while で fish のインデックスを手動管理する必要がある。