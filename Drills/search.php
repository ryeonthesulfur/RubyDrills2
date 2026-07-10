<?php

function search($target_num, $input) {
    // foreachで配列をループします。$indexに添字、 $numに値が入ります。
    foreach ($input as $index => $num) {
        // 値がターゲットと一致するかチェック
        if ($num == $target_num) {
            // 見つかったら1ベースの添字を出力して、returnで関数を終了
            echo ($index + 1) . "番目にあります\n";
            return;
        }
    }

    // ループが最後まで終わっても見つからなかった場合（returnされなかった場合）にここが実行される
    echo "その数は含まれていません\n";
}

$input = [3, 5, 9 ,12, 15, 21, 29, 35, 42, 51, 62, 78, 81, 87, 92, 93];

// 実行例
search(11, $input); // 見つからない場合の例
search(15, $input); // 見つかる場合の例
