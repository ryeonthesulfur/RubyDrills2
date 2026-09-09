# ドリル２ コード比較（Ruby）

---

## パターン１：`show` メソッドでクラス内から表示する

クラスの中に `show` メソッドを定義して、インスタンスメソッドとして呼び出す。  
`@変数` はクラス内のどこからでも使えるので、ゲッターなしでそのまま参照できる。

```ruby
class Article
  def initialize(author, title, content)
    @author = author
    @title = title
    @content = content
  end

  def show
    puts "著者: #{@author}"
    puts "タイトル: #{@title}"
    puts "本文: #{@content}"
  end
end

article = Article.new("阿部", "Rubyの素晴らしさについて", "Awesome Ruby!")
article.show
```

### 別解：`attr_reader` でクラスの外から呼び出す

`attr_reader` でゲッターを自動生成して、クラスの外から値を取り出す書き方。

```ruby
class Article
  attr_reader :author, :title, :content

  def initialize(author, title, content)
    @author = author
    @title = title
    @content = content
  end
end

article = Article.new("阿部", "Rubyの素晴らしさについて", "Awesome Ruby!")
puts "著者: #{article.author}"
puts "タイトル: #{article.title}"
puts "本文: #{article.content}"
```

---

## パターン２：`attr_accessor` で値の読み書きをする

`attr_accessor` はゲッター（読み取り）＋セッター（書き込み）を両方自動生成する。  
インスタンス作成後に値を変更したい場合に使う。

```ruby
class Article
  attr_accessor :author, :title, :content

  def initialize(author, title, content)
    @author = author
    @title = title
    @content = content
  end
end

article = Article.new("阿部", "Rubyの素晴らしさについて", "Awesome Ruby!")
puts "変更前の著者: #{article.author}"
article.author = "田中"
puts "変更後の著者: #{article.author}"
```

### 別解：ゲッターとセッターを手書きした場合

`attr_accessor` が裏でやっていることを手書きで再現したバージョン。動きは上と全く同じ。

```ruby
class Article
  def author        # ゲッター（読み取り用）
    @author
  end

  def author=(new_author)   # セッター（書き込み用）
    @author = new_author
  end

  def initialize(author, title, content)
    @author = author
    @title = title
    @content = content
  end
end

article = Article.new("阿部", "Rubyの素晴らしさについて", "Awesome Ruby!")
puts "変更前の著者: #{article.author}"
article.author = "田中"
puts "変更後の著者: #{article.author}"
```

---

## ３つの書き方まとめ

| 書き方 | 読み取り | 書き換え | 用途 |
|:-------|:--------:|:--------:|:-----|
| `show` メソッド内で `@変数` を使う | クラス内のみ | − | 表示処理をクラスにまとめたいとき |
| `attr_reader` | ○（外からOK） | × | 外から読むだけでいいとき |
| `attr_accessor` | ○ | ○ | 外から読み書き両方したいとき |
