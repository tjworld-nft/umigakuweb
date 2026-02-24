<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// ブログAPIの基本URL（暫定的に無効化してフォールバック表示を使用）
$blog_api_url = 'https://example-invalid-url.com/api/posts'; // 意図的に無効なURLに変更

try {
    // 外部ブログAPIから最新記事を取得
    $context = stream_context_create([
        'http' => [
            'timeout' => 10
        ]
    ]);
    
    $response = file_get_contents($blog_api_url, false, $context);
    
    if ($response === FALSE) {
        throw new Exception('ブログAPIへの接続に失敗しました');
    }
    
    $blog_data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('ブログデータの解析に失敗しました');
    }
    
    // 日付でソートして最新3件の記事を取得
    usort($blog_data, function($a, $b) {
        $dateA = $a['publishedAt'] ?? $a['date'] ?? '1970-01-01';
        $dateB = $b['publishedAt'] ?? $b['date'] ?? '1970-01-01';
        return strtotime($dateB) - strtotime($dateA); // 新しい順にソート
    });
    
    $latest_posts = array_slice($blog_data, 0, 3);
    
    // レスポンス用にデータを整形
    $formatted_posts = [];
    foreach ($latest_posts as $post) {
        $formatted_posts[] = [
            'id' => $post['id'] ?? uniqid(),
            'title' => $post['title'] ?? 'タイトルなし',
            'excerpt' => $post['excerpt'] ?? substr(strip_tags($post['content'] ?? ''), 0, 100) . '...',
            'date' => $post['publishedAt'] ?? $post['date'] ?? date('Y-m-d'),
            'url' => '/blog/' . ($post['slug'] ?? $post['id'] ?? '#'),
            'image' => $post['image'] ?? null
        ];
    }
    
    // 成功レスポンス
    echo json_encode([
        'success' => true,
        'posts' => $formatted_posts,
        'count' => count($formatted_posts)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // エラー時のフォールバック記事（最新情報で更新）
    $fallback_posts = [
        [
            'id' => 'latest-1',
            'title' => '2025年冬のダイビングシーズン到来！三浦の海を満喫しよう',
            'excerpt' => '冬の三浦の海は透明度抜群！寒い季節だからこそ楽しめる特別なダイビング体験をご紹介します。',
            'date' => '2025-01-08',
            'url' => '/blog/',
            'image' => null
        ],
        [
            'id' => 'latest-2', 
            'title' => 'TJ Music × 海の癒し - 新しい音楽体験が始まります',
            'excerpt' => '海の美しさと音楽の魅力を融合した特別な世界。日常に海の癒しを取り入れる新しい方法をお届けします。',
            'date' => '2025-01-07',
            'url' => '/blog/',
            'image' => null
        ],
        [
            'id' => 'latest-3',
            'title' => '吉田の最新著書12冊がKindle Unlimited読み放題で登場',
            'excerpt' => 'ダイビング・マリンアクティビティから絵本まで、幅広いジャンルの書籍がKindle Unlimitedで無料読み放題になりました。',
            'date' => '2025-01-06',
            'url' => '/blog/',
            'image' => null
        ]
    ];
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'posts' => $fallback_posts,
        'count' => count($fallback_posts)
    ], JSON_UNESCAPED_UNICODE);
}
?>