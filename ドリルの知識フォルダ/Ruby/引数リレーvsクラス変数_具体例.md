# 引数リレー vs クラス変数：データの追加から表示までの具体例

---

## 目次

1. [引数リレー方式：配列を外に置いて引数で渡す](#1-引数リレー方式配列を外に置いて引数で渡す)
2. [クラス変数方式：配列をクラスの中に持つ](#2-クラス変数方式配列をクラスの中に持つ)
3. [2つの方式の対応表](#3-2つの方式の対応表)

---

## 1. 引数リレー方式：配列を外に置いて引数で渡す

```ruby
# ==========================================
# データの入れ物（配列）はクラスの外に用意する
# ==========================================
cart = []

# ---- データを追加するメソッド ----
def add_item(a_cart)
  print "商品名を入力: "
  name = gets.chomp
  print "値段を入力: "
  price = gets.to_i

  new_item = { name: name, price: price }  # ハッシュとして作る
  a_cart << new_item                        # 引数で受け取った配列に追加
  return a_cart                             # 追加した配列を返す（必須！）
end

# ---- データを表示するメソッド ----
def show_items(a_cart)                      # こちらも配列を引数で受け取る
  a_cart.each do |item|
    puts "#{item[:name]}：#{item[:price]}円"  # ハッシュなので [:キー] で取り出す
  end
end

# ==========================================
# メインループ
# ==========================================
while true
  puts "1: 追加  2: 一覧  0: 終了"
  input = gets.to_i

  if input == 1
    cart = add_item(cart)    # cart を渡して、戻り値で cart を上書きする
  elsif input == 2
    show_items(cart)         # こちらも cart を渡す必要がある
  elsif input == 0
    break
  end
end
```

**実行の流れ（追加 → 表示）:**

```
追加1回目: cart = add_item(cart)
  → cartの中身: [ { name: "ラーメン", price: 500 } ]

追加2回目: cart = add_item(cart)
  → cartの中身: [ { name: "ラーメン", price: 500 }, { name: "カレー", price: 700 } ]

表示: show_items(cart)
  → ラーメン：500円
  → カレー：700円
```

---

## 2. クラス変数方式：配列をクラスの中に持つ

```ruby
# ==========================================
# データの入れ物（配列）はクラスの中に持つ
# ==========================================
class Food
  @@foods = []                    # クラス変数：Foodクラスが自分で管理する配列
  attr_reader :name, :calory      # インスタンス変数を外から読めるようにする

  def initialize(name, calory)
    @name   = name
    @calory = calory
  end

  # ---- データを追加するクラスメソッド ----
  def self.input
    print "食べ物の名前を入力: "
    name = gets.chomp
    print "カロリーを入力: "
    calory = gets.to_i

    food = Food.new(name, calory)  # Foodオブジェクトを作る
    @@foods << food                # 引数で渡さなくても直接 @@foods に追加できる
                                   # （return不要）
  end

  # ---- データを表示するクラスメソッド ----
  def self.show_all
                                   # こちらも引数不要。@@foods を直接参照する
    @@foods.each do |food|
      puts "#{food.name}：#{food.calory}kcal"  # オブジェクトなので .メソッド で取り出す
    end
  end
end

# ==========================================
# メインループ
# ==========================================
while true
  puts "1: 追加  2: 一覧  0: 終了"
  input = gets.to_i

  if input == 1
    Food.input     # 配列を渡す必要なし。クラスに「追加して」とお願いするだけ
  elsif input == 2
    Food.show_all  # 配列を渡す必要なし。クラスに「見せて」とお願いするだけ
  elsif input == 0
    break
  end
end
```

**実行の流れ（追加 → 表示）:**

```
追加1回目: Food.input
  → @@foods の中身: [ <Food @name="メロンパン", @calory=300> ]

追加2回目: Food.input
  → @@foods の中身: [ <Food @name="メロンパン", @calory=300>, <Food @name="サラダ", @calory=80> ]

表示: Food.show_all
  → メロンパン：300kcal
  → サラダ：80kcal
```

---

## 3. 2つの方式の対応表

| 比較ポイント | 引数リレー方式 | クラス変数方式 |
|:-------------|:--------------|:--------------|
| **配列の置き場所** | クラスの外（変数 `cart`） | クラスの中（`@@foods`） |
| **データの追加** | `cart = add_item(cart)` | `Food.input`（引数なし） |
| **データの表示** | `show_items(cart)` | `Food.show_all`（引数なし） |
| **配列を渡す必要** | 毎回必要 | 不要 |
| **戻り値で受け取る必要** | 必要（`cart = ...`） | 不要 |
| **データの形式** | ハッシュ `{ name: "...", price: 0 }` | オブジェクト `<Food @name="...">` |
| **値の取り出し方** | `item[:name]` | `food.name` |
