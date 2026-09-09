# 【概説】initialize メソッドとは

## ひとことで言うと

`initialize` は、**インスタンスが生まれた瞬間に自動で呼ばれるメソッド**。  
`.new` を呼んだ瞬間に必ず実行される。

```ruby
article = Article.new("阿部", "タイトル", "本文")
#                  ↑ この瞬間に initialize が動く
```

---

## 何のために使うのか

クラスから作ったインスタンスは、最初は「空の箱」。  
`initialize` はその箱に**最初のデータをセットするための初期化処理**を書く場所。

```ruby
class Article
  def initialize(author, title, content)
    @author  = author   # インスタンス変数に値を入れる
    @title   = title
    @content = content
  end
end

article = Article.new("阿部", "Rubyの素晴らしさ", "Awesome Ruby!")
# → @author = "阿部", @title = "Rubyの素晴らしさ", @content = "Awesome Ruby!" がセットされた状態で誕生
```

---

## .new と initialize の関係

| 書くもの | 役割 |
|---|---|
| `Article.new(...)` | 外から呼ぶ。インスタンスを生成する命令 |
| `def initialize(...)` | 内部で自動実行される。初期値をセットする処理 |

`.new` に渡した引数が、そのまま `initialize` の引数として渡される。

---

## initialize の3つの書き方

このフォルダには書き方ごとの詳細ファイルがある。概要だけ把握しておく。

### ① 引数でそのままセット（基本形）
```ruby
def initialize(author, title, content)
  @author  = author
  @title   = title
  @content = content
end

Article.new("阿部", "タイトル", "本文")
```
→ シンプルで一番よく見る。詳細: `initialize メソッドのいろんな記述方法.txt`

---

### ② initialize を空にして、後からセッターで入れる
```ruby
def initialize
end

article = Article.new
article.author = "阿部"   # 後からセッターで入れる
```
→ 柔軟だが、インスタンス生成後に手動でセットが必要。詳細: `セッター関連/` フォルダ参照

---

### ③ ハッシュ（attributes）でまとめて受け取る
```ruby
def initialize(attributes = {})
  self.author  = attributes[:author]
  self.title   = attributes[:title]
  self.content = attributes[:content]
end

Article.new({ author: "阿部", title: "タイトル", content: "本文" })
```
→ Rails の `Article.new(params)` はこのパターン。詳細: `initialize メソッドのいろんな記述方法.txt`

---

## initialize が「コンストラクタ」と呼ばれる理由

他の言語（Java / PHP など）では同じ役割のものを**コンストラクタ**と呼ぶ。  
「インスタンスを構築（construct）するためのメソッド」という意味。  
Ruby では名前が `initialize` に固定されているだけで、やっていることは同じ。

詳細: `コンストラクタ関連/コンストラクタ.txt`

---

## まとめ

| ポイント | 内容 |
|---|---|
| いつ呼ばれる？ | `.new` を呼んだ瞬間に自動で |
| 何をする？ | インスタンス変数に初期値をセットする |
| 引数は？ | `.new(...)` に渡した値がそのまま来る |
| 別名 | コンストラクタ（Java / PHP での呼び方） |
| セッターとの関係 | initialize の中でセッターを呼ぶこともできる |
