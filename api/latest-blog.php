<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// ブログAPIの基本URL（Sanity CMS）
$blog_api_url = 'https://miura-blog-site.vercel.app/api/posts';

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
    
    // 最新3件の記事を取得
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
    // エラー時のフォールバック記事
    $fallback_posts = [
        [
            'id' => 'fallback-1',
            'title' => '三浦の海で体験ダイビング',
            'excerpt' => '初心者でも安心！三浦の美しい海で体験ダイビングを楽しみませんか？',
            'date' => date('Y-m-d'),
            'url' => '/blog/',
            'image' => null
        ],
        [
            'id' => 'fallback-2', 
            'title' => 'PADI講習のご案内',
            'excerpt' => 'PADI5つ星IDCセンターで、安全で質の高いダイビング講習を受講できます。',
            'date' => date('Y-m-d'),
            'url' => '/blog/',
            'image' => null
        ],
        [
            'id' => 'fallback-3',
            'title' => 'マリンアクティビティ情報',
            'excerpt' => 'シーカヤック、SUP、スノーケリングなど、海の楽しみ方をご紹介します。',
            'date' => date('Y-m-d'),
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