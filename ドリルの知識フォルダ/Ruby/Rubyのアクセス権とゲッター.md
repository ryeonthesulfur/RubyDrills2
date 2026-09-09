# Rubyのアクセス権とゲッター

---

## 目次

1. [インスタンス変数はデフォルトで非公開](#1-インスタンス変数はデフォルトで非公開)
2. [外から読みたい時は attr_reader を使う](#2-外から読みたい時はattr_readerを使う)
3. [Rubyのprivateはメソッドを隠すために使う](#3-rubyのprivateはメソッドを隠すために使う)
4. [後書き：PHPとの比較](#後書きphpとの比較)

---

## 1. インスタンス変数はデフォルトで非公開

Rubyのインスタンス変数（`@` のついた変数）は、**最初から問答無用で外部から触れない**というルールになっている。

```ruby
class Article
  def initialize(author)
    @author = author  # この変数は外からは読めない
  end
end

article = Article.new("田中")
puts article.author  # => エラー！ NoMethodError
puts article.@author # => これも書けない（構文エラー）
```

`@author` という変数は `Article` クラスの内側にしか存在しない。外からのぞく手段がそもそも用意されていない状態。

---

## 2. 外から読みたい時は attr_reader を使う

外から値を読めるようにしたい場合は、`attr_reader` で**ゲッターメソッドを自動生成**する。

```ruby
class Article
  attr_reader :author  # これを書くだけで外から .author で読めるようになる

  def initialize(author)
    @author = author
  end
end

article = Article.new("田中")
puts article.author  #=> "田中"
```

`attr_reader :author` は、内部で以下のメソッドを自動生成している。

```ruby
def author
  @author
end
```

つまり `attr_reader` は「変数を公開する」のではなく、「**変数の中身を返すメソッドを作る**」機能。直接変数を見せているわけではなく、あくまでメソッドという受付窓口を通じて返している。

---

## 3. Rubyの private はメソッドを隠すために使う

`private` というキーワードはRubyにも存在するが、**変数ではなくメソッドを隠すため**に使う。

```ruby
class Article
  def show
    puts "著者：#{secret_format}"  # クラスの内側からは呼べる
  end

  private

  def secret_format
    # 外から article.secret_format としては呼び出せない
    "【#{@author}】"
  end
end
```

`private` より下に書いたメソッドは、クラスの外から呼べなくなる。外に公開する必要のない「内部の補助メソッド」を隠す用途で使う。

---

## 後書き：PHPとの比較

PHPから来た場合に混乱しやすいポイントをまとめる。

### PHPとRubyの変数アクセスの違い

PHPでは、変数のアクセス権を毎回 `public` / `private` で明示しなければならない。Rubyはその宣言が不要で、最初から非公開になっている。

| 比較 | PHP | Ruby |
|:-----|:----|:-----|
| 変数の初期状態 | 宣言時に `public`/`private` を選ぶ | 最初から非公開（宣言不要） |
| 外から読めるようにする方法 | ゲッターメソッドを手書きする | `attr_reader` 1行で自動生成 |
| `private` の対象 | 変数にもメソッドにも使う | メソッドだけに使う |

### PHPの `$this` と Rubyの `@` の対応

PHPのゲッターメソッドの中に出てくる `$this->author` は、Rubyの `@author` と同じ意味。

```php
// PHP
public function getAuthor() {
    return $this->author;  // これは Rubyの @author と同じ
}
```

```ruby
# Ruby
def author
  @author  # PHPの $this->author と同じ意味
end
```

PHPでは `$author` と書くだけではローカル変数とみなされてしまうため、「クラスが持っている変数だ」と示すために `$this->` を毎回つける必要がある。Rubyは `@` をつけるだけで「このクラスのインスタンス変数だ」と識別される。

```
PHPの $this->author   ＝  Rubyの @author     （変数へのアクセス）
PHPの $this->author() ＝  Rubyの self.author  （メソッドの呼び出し）
```
