# `scan` + `length` で文字列の出現回数を数える

---

## やりたいこと

文字列の中に、特定の文字列が何回出現するかを数える。

---

## 書き方

```ruby
str.scan("hi").length
```

---

## 動きの仕組み

2段階の処理になっている。

**① `scan("hi")` で一致する部分をすべて配列にまとめる**

```ruby
"ABChi hi".scan("hi")  #=> ["hi", "hi"]
"hihi".scan("hi")      #=> ["hi", "hi"]
"abc hi ho".scan("hi") #=> ["hi"]
```

**② `.length` でその配列の要素数を数える**

```ruby
["hi", "hi"].length  #=> 2
["hi"].length        #=> 1
```

---

## コード例

```ruby
def count_hi(str)
  puts str.scan("hi").length
end

count_hi("abc hi ho")  #=> 1
count_hi("ABChi hi")   #=> 2
count_hi("hihi")       #=> 2
```

---

## ポイント

- `scan` は見つかった部分文字列を全て集めて**配列**にして返す
- 見つからなかった場合は空の配列 `[]` を返す
- `.length` はその配列の要素数 ＝ 出現回数になる
