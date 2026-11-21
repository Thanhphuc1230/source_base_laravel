<?php

namespace App\Services;

use Illuminate\Support\Str;

class SeoAnalysisService
{
    /**
     * Phân tích và chấm điểm SEO của bài viết, sản phẩm hoặc danh mục trên thang điểm 100.
     *
     * @param array $data Dữ liệu cần phân tích
     * @return array Kết quả chi tiết và điểm số
     */
    public function analyze(array $data): array
    {
        $title = trim($data['name_vn'] ?? $data['title'] ?? '');
        $slug = trim($data['slug_vn'] ?? $data['slug'] ?? '');
        $keyword = trim($data['keyword_vn'] ?? $data['keyword'] ?? $data['focus_keyword'] ?? '');
        $description = trim($data['description_vn'] ?? $data['description'] ?? '');
        $intro = trim($data['intro_vn'] ?? $data['intro'] ?? '');
        $content = trim($data['content_vn'] ?? $data['content'] ?? '');
        $hasImage = !empty($data['image_vn'] ?? $data['image'] ?? false);

        // Kiểm tra xem có phải phân tích danh mục (không có trường nội dung chi tiết)
        $isCategory = isset($data['is_category']) ? (bool) $data['is_category'] : (!array_key_exists('content_vn', $data) && !array_key_exists('content', $data));

        // Chuẩn hóa từ khóa chính (Lấy từ khóa đầu tiên nếu nhập chuỗi cách nhau bởi dấu phẩy)
        $focusKeyword = $this->extractPrimaryKeyword($keyword);

        if ($isCategory) {
            return $this->analyzeCategory($title, $slug, $description, $focusKeyword, $hasImage);
        }

        // Khởi tạo các nhóm tiêu chí cho Bài viết & Sản phẩm (Content Mode)
        $basic = $this->evaluateBasicSeo($title, $slug, $description, $intro, $content, $focusKeyword);
        $additional = $this->evaluateAdditionalSeo($content, $slug, $focusKeyword, $hasImage);
        $readability = $this->evaluateReadability($title, $description, $content);
        $links = $this->evaluateLinks($content);

        $totalScore = $basic['score'] + $additional['score'] + $readability['score'] + $links['score'];
        $totalScore = max(0, min(100, (int) round($totalScore)));

        // Đánh giá xếp loại theo tiêu chuẩn Rank Math / Yoast SEO
        $rating = 'bad';
        $ratingText = 'Cần cải thiện (Kém)';
        $badgeClass = 'danger';

        if ($totalScore >= 80) {
            $rating = 'good';
            $ratingText = 'Tốt / Chuẩn SEO';
            $badgeClass = 'success';
        } elseif ($totalScore >= 50) {
            $rating = 'ok';
            $ratingText = 'Khá / Cần tối ưu thêm';
            $badgeClass = 'warning';
        }

        return [
            'score' => $totalScore,
            'rating' => $rating,
            'rating_text' => $ratingText,
            'badge_class' => $badgeClass,
            'is_category' => false,
            'focus_keyword' => $focusKeyword,
            'categories' => [
                'basic' => $basic,
                'additional' => $additional,
                'readability' => $readability,
                'links' => $links,
            ],
            'metrics' => [
                'word_count' => $this->countWords($content),
                'keyword_density' => $this->calculateKeywordDensity($content, $focusKeyword),
                'title_length' => mb_strlen($title, 'UTF-8'),
                'description_length' => mb_strlen($description, 'UTF-8'),
                'headings_count' => $this->countHeadings($content),
                'images_count' => $this->countImages($content),
                'internal_links_count' => $this->countInternalLinks($content),
                'external_links_count' => $this->countExternalLinks($content),
            ]
        ];
    }

    /**
     * Phân tích điểm SEO chuyên biệt cho Danh mục / Taxonomy (100 điểm).
     */
    protected function analyzeCategory(string $title, string $slug, string $desc, string $keyword, bool $hasImage): array
    {
        $hasKeyword = !empty($keyword);
        $titleLen = mb_strlen($title, 'UTF-8');
        $descLen = mb_strlen($desc, 'UTF-8');
        $slugLen = mb_strlen($slug, 'UTF-8');
        $slugKeyword = Str::slug($keyword);

        // 1. Nhóm SEO Cơ bản (40 điểm)
        $basicItems = [];
        $basicScore = 0;

        // 1.1 Từ khóa trong tiêu đề (15đ)
        $inTitle = $hasKeyword && $this->containsText($title, $keyword);
        $s1_1 = $inTitle ? 15 : 0;
        $basicScore += $s1_1;
        $basicItems[] = [
            'id' => 'keyword_in_title',
            'label' => 'Từ khóa chính trong Tên danh mục',
            'passed' => $inTitle,
            'status' => $inTitle ? 'pass' : ($hasKeyword ? 'fail' : 'warning'),
            'score' => $s1_1,
            'max_score' => 15,
            'message' => $inTitle ? 'Tên danh mục đã chứa từ khóa mục tiêu.' : 'Tên danh mục chưa chứa từ khóa mục tiêu.',
        ];

        // 1.2 Từ khóa ở đầu tiêu đề (10đ)
        $atStart = false;
        if ($inTitle) {
            $pos = mb_stripos($this->removeAccents($title), $this->removeAccents($keyword), 0, 'UTF-8');
            $atStart = ($pos !== false && $pos <= $titleLen / 2);
        }
        $s1_2 = $atStart ? 10 : ($inTitle ? 5 : 0);
        $basicScore += $s1_2;
        $basicItems[] = [
            'id' => 'keyword_at_start_of_title',
            'label' => 'Từ khóa ở phần đầu Tên danh mục',
            'passed' => $atStart,
            'status' => $atStart ? 'pass' : ($inTitle ? 'warning' : 'fail'),
            'score' => $s1_2,
            'max_score' => 10,
            'message' => $atStart ? 'Rất tốt! Từ khóa nằm ở nửa đầu tiêu đề.' : 'Nên đặt từ khóa chính gần đầu tên danh mục.',
        ];

        // 1.3 Từ khóa trong mô tả Meta (15đ)
        $inDesc = $hasKeyword && $this->containsText($desc, $keyword);
        $s1_3 = $inDesc ? 15 : 0;
        $basicScore += $s1_3;
        $basicItems[] = [
            'id' => 'keyword_in_meta_description',
            'label' => 'Từ khóa trong Thẻ mô tả Meta',
            'passed' => $inDesc,
            'status' => $inDesc ? 'pass' : ($hasKeyword ? 'fail' : 'warning'),
            'score' => $s1_3,
            'max_score' => 15,
            'message' => $inDesc ? 'Thẻ mô tả danh mục đã chứa từ khóa chính.' : 'Thẻ mô tả danh mục chưa có từ khóa chính.',
        ];

        // 2. Nhóm Đường dẫn & Hình ảnh (30 điểm)
        $additionalItems = [];
        $additionalScore = 0;

        // 2.1 Từ khóa trong Slug (15đ)
        $inSlug = $hasKeyword && !empty($slug) && str_contains($slug, $slugKeyword);
        $s2_1 = $inSlug ? 15 : 0;
        $additionalScore += $s2_1;
        $additionalItems[] = [
            'id' => 'keyword_in_slug',
            'label' => 'Từ khóa trong Đường dẫn (Slug)',
            'passed' => $inSlug,
            'status' => $inSlug ? 'pass' : 'fail',
            'score' => $s2_1,
            'max_score' => 15,
            'message' => $inSlug ? 'Đường dẫn danh mục chứa từ khóa chính không dấu.' : 'Đường dẫn (slug) chưa chứa từ khóa chính.',
        ];

        // 2.2 Độ dài Slug thân thiện (10đ)
        $s2_2 = ($slugLen >= 3 && $slugLen <= 75) ? 10 : ($slugLen > 75 ? 4 : 0);
        $additionalScore += $s2_2;
        $additionalItems[] = [
            'id' => 'slug_length',
            'label' => 'Độ dài URL / Slug thân thiện (<= 75 ký tự)',
            'passed' => $s2_2 >= 4,
            'status' => $s2_2 === 10 ? 'pass' : ($s2_2 > 0 ? 'warning' : 'fail'),
            'score' => $s2_2,
            'max_score' => 10,
            'message' => $s2_2 === 10 ? "Độ dài slug ({$slugLen} ký tự) rất chuẩn." : 'Slug quá dài hoặc chưa có slug.',
        ];

        // 2.3 Hình ảnh / Banner danh mục (5đ)
        $s2_3 = $hasImage ? 5 : 0;
        $additionalScore += $s2_3;
        $additionalItems[] = [
            'id' => 'images_and_alt',
            'label' => 'Hình ảnh đại diện / Banner danh mục',
            'passed' => $hasImage,
            'status' => $hasImage ? 'pass' : 'warning',
            'score' => $s2_3,
            'max_score' => 5,
            'message' => $hasImage ? 'Danh mục đã có ảnh đại diện/banner trực quan.' : 'Nên tải lên ảnh đại diện hoặc banner cho danh mục.',
        ];

        // 3. Nhóm Khả năng hiển thị & Trình bày (30 điểm)
        $readabilityItems = [];
        $readabilityScore = 0;

        // 3.1 Độ dài Tiêu đề chuẩn (15đ)
        if ($titleLen >= 30 && $titleLen <= 65) {
            $s3_1 = 15;
            $s3_1Status = 'pass';
            $s3_1Msg = "Tiêu đề ({$titleLen} ký tự) hiển thị trọn vẹn trên Google SERP.";
        } elseif ($titleLen >= 20 && $titleLen <= 75) {
            $s3_1 = 8;
            $s3_1Status = 'warning';
            $s3_1Msg = "Tiêu đề ({$titleLen} ký tự) hơi ngắn hoặc hơi dài (khuyên dùng 30-65 ký tự).";
        } else {
            $s3_1 = 0;
            $s3_1Status = 'fail';
            $s3_1Msg = "Tiêu đề ({$titleLen} ký tự) quá ngắn hoặc quá dài.";
        }
        $readabilityScore += $s3_1;
        $readabilityItems[] = [
            'id' => 'title_length',
            'label' => 'Độ dài tiêu đề danh mục (30 - 65 ký tự)',
            'passed' => $s3_1 >= 8,
            'status' => $s3_1Status,
            'score' => $s3_1,
            'max_score' => 15,
            'message' => $s3_1Msg,
        ];

        // 3.2 Độ dài Mô tả Meta chuẩn (15đ)
        if ($descLen >= 100 && $descLen <= 160) {
            $s3_2 = 15;
            $s3_2Status = 'pass';
            $s3_2Msg = "Mô tả danh mục ({$descLen}/160 ký tự) chuẩn xác.";
        } elseif (($descLen >= 50 && $descLen < 100) || ($descLen > 160 && $descLen <= 180)) {
            $s3_2 = 8;
            $s3_2Status = 'warning';
            $s3_2Msg = "Mô tả ({$descLen} ký tự) nên điều chỉnh về khoảng 100 - 160 ký tự.";
        } else {
            $s3_2 = 0;
            $s3_2Status = 'fail';
            $s3_2Msg = "Mô tả danh mục ({$descLen} ký tự) chưa đạt chuẩn độ dài.";
        }
        $readabilityScore += $s3_2;
        $readabilityItems[] = [
            'id' => 'desc_length',
            'label' => 'Độ dài thẻ mô tả Meta (100 - 160 ký tự)',
            'passed' => $s3_2 >= 8,
            'status' => $s3_2Status,
            'score' => $s3_2,
            'max_score' => 15,
            'message' => $s3_2Msg,
        ];

        $totalScore = $basicScore + $additionalScore + $readabilityScore;
        $totalScore = max(0, min(100, (int) round($totalScore)));

        $rating = 'bad';
        $ratingText = 'Cần cải thiện (Kém)';
        $badgeClass = 'danger';

        if ($totalScore >= 80) {
            $rating = 'good';
            $ratingText = 'Tốt / Chuẩn SEO';
            $badgeClass = 'success';
        } elseif ($totalScore >= 50) {
            $rating = 'ok';
            $ratingText = 'Khá / Cần tối ưu thêm';
            $badgeClass = 'warning';
        }

        return [
            'score' => $totalScore,
            'rating' => $rating,
            'rating_text' => $ratingText,
            'badge_class' => $badgeClass,
            'is_category' => true,
            'focus_keyword' => $keyword,
            'categories' => [
                'basic' => [
                    'title' => 'SEO Cơ bản',
                    'score' => $basicScore,
                    'max_score' => 40,
                    'items' => $basicItems,
                ],
                'additional' => [
                    'title' => 'Đường dẫn & Hình ảnh',
                    'score' => $additionalScore,
                    'max_score' => 30,
                    'items' => $additionalItems,
                ],
                'readability' => [
                    'title' => 'Khả năng hiển thị Google',
                    'score' => $readabilityScore,
                    'max_score' => 30,
                    'items' => $readabilityItems,
                ],
            ],
            'metrics' => [
                'title_length' => $titleLen,
                'description_length' => $descLen,
                'slug_length' => $slugLen,
                'has_image' => $hasImage,
            ]
        ];
    }

    /**
     * Tính toán nhanh chỉ lấy điểm số (0 - 100).
     */
    public function calculateScore(array $data): int
    {
        $analysis = $this->analyze($data);
        return $analysis['score'];
    }

    /**
     * Đánh giá SEO Cơ bản cho Bài viết & Sản phẩm (Tối đa 40 điểm).
     */
    protected function evaluateBasicSeo(string $title, string $slug, string $desc, string $intro, string $content, string $keyword): array
    {
        $items = [];
        $score = 0;
        $maxScore = 40;

        $hasKeyword = !empty($keyword);

        // 1. Từ khóa trong tiêu đề (10đ)
        $inTitle = $hasKeyword && $this->containsText($title, $keyword);
        $itemScore = $inTitle ? 10 : 0;
        $score += $itemScore;
        $items[] = [
            'id' => 'keyword_in_title',
            'label' => 'Từ khóa chính trong Tiêu đề SEO',
            'passed' => $inTitle,
            'status' => $inTitle ? 'pass' : ($hasKeyword ? 'fail' : 'warning'),
            'score' => $itemScore,
            'max_score' => 10,
            'message' => $inTitle
                ? 'Tuyệt vời! Tiêu đề đã chứa từ khóa chính.'
                : ($hasKeyword ? 'Tiêu đề chưa chứa từ khóa chính "' . $keyword . '".' : 'Chưa nhập từ khóa SEO mục tiêu.'),
        ];

        // 2. Từ khóa nằm ở đầu tiêu đề (5đ)
        $atStart = false;
        if ($inTitle) {
            $pos = mb_stripos($this->removeAccents($title), $this->removeAccents($keyword), 0, 'UTF-8');
            $halfLength = mb_strlen($title, 'UTF-8') / 2;
            $atStart = ($pos !== false && $pos <= $halfLength);
        }
        $itemScore = $atStart ? 5 : ($inTitle ? 2 : 0);
        $score += $itemScore;
        $items[] = [
            'id' => 'keyword_at_start_of_title',
            'label' => 'Từ khóa xuất hiện ở phần đầu Tiêu đề',
            'passed' => $atStart,
            'status' => $atStart ? 'pass' : ($inTitle ? 'warning' : 'fail'),
            'score' => $itemScore,
            'max_score' => 5,
            'message' => $atStart
                ? 'Rất tốt! Từ khóa chính nằm ở nửa đầu của tiêu đề.'
                : 'Nên đặt từ khóa chính gần đầu tiêu đề để tăng CTR trên Google.',
        ];

        // 3. Từ khóa trong Thẻ mô tả Meta (8đ)
        $inDesc = $hasKeyword && $this->containsText($desc, $keyword);
        $itemScore = $inDesc ? 8 : 0;
        $score += $itemScore;
        $items[] = [
            'id' => 'keyword_in_meta_description',
            'label' => 'Từ khóa chính trong Thẻ mô tả Meta (Description)',
            'passed' => $inDesc,
            'status' => $inDesc ? 'pass' : ($hasKeyword ? 'fail' : 'warning'),
            'score' => $itemScore,
            'max_score' => 8,
            'message' => $inDesc
                ? 'Chính xác! Thẻ mô tả Meta đã xuất hiện từ khóa chính.'
                : 'Thẻ mô tả Meta chưa chứa từ khóa chính.',
        ];

        // 4. Từ khóa trong Đường dẫn / Slug (7đ)
        $slugKeyword = Str::slug($keyword);
        $inSlug = $hasKeyword && !empty($slug) && str_contains($slug, $slugKeyword);
        $itemScore = $inSlug ? 7 : 0;
        $score += $itemScore;
        $items[] = [
            'id' => 'keyword_in_slug',
            'label' => 'Từ khóa chính trong Đường dẫn (URL/Slug)',
            'passed' => $inSlug,
            'status' => $inSlug ? 'pass' : 'fail',
            'score' => $itemScore,
            'max_score' => 7,
            'message' => $inSlug
                ? 'Chuẩn SEO! Đường dẫn URL chứa từ khóa chính thân thiện.'
                : 'Đường dẫn (slug) chưa chứa từ khóa chính dạng không dấu.',
        ];

        // 5. Từ khóa ở đoạn đầu bài viết (Intro hoặc 10% đầu content) (5đ)
        $earlyContent = $intro . ' ' . mb_substr(strip_tags($content), 0, 400, 'UTF-8');
        $inEarly = $hasKeyword && $this->containsText($earlyContent, $keyword);
        $itemScore = $inEarly ? 5 : 0;
        $score += $itemScore;
        $items[] = [
            'id' => 'keyword_in_intro',
            'label' => 'Từ khóa xuất hiện trong đoạn mở đầu (10% đầu bài)',
            'passed' => $inEarly,
            'status' => $inEarly ? 'pass' : 'fail',
            'score' => $itemScore,
            'max_score' => 5,
            'message' => $inEarly
                ? 'Xuất sắc! Từ khóa xuất hiện ngay trong phần giới thiệu / đoạn mở đầu.'
                : 'Hãy nhắc đến từ khóa chính trong đoạn mở đầu hoặc Sapo của bài viết/sản phẩm.',
        ];

        // 6. Độ dài nội dung bài viết/sản phẩm (5đ)
        $wordCount = $this->countWords($content);
        if ($wordCount >= 600) {
            $lenScore = 5;
            $lenStatus = 'pass';
            $lenMsg = "Độ dài nội dung đạt {$wordCount} từ (Chuẩn khuyến nghị >= 600 từ).";
        } elseif ($wordCount >= 300) {
            $lenScore = 3;
            $lenStatus = 'warning';
            $lenMsg = "Nội dung có {$wordCount} từ (Khá, nên bổ sung nội dung lên trên 600 từ).";
        } else {
            $lenScore = 0;
            $lenStatus = 'fail';
            $lenMsg = "Nội dung chỉ có {$wordCount} từ (Quá ngắn, chuẩn SEO cần tối thiểu 300-600 từ).";
        }
        $score += $lenScore;
        $items[] = [
            'id' => 'content_length',
            'label' => 'Độ dài nội dung tối thiểu',
            'passed' => $lenScore >= 3,
            'status' => $lenStatus,
            'score' => $lenScore,
            'max_score' => 5,
            'message' => $lenMsg,
        ];

        return [
            'title' => 'SEO Cơ bản',
            'score' => $score,
            'max_score' => $maxScore,
            'items' => $items,
        ];
    }

    /**
     * Đánh giá SEO Bổ sung cho Bài viết & Sản phẩm (Tối đa 30 điểm).
     */
    protected function evaluateAdditionalSeo(string $content, string $slug, string $keyword, bool $hasFeaturedImage): array
    {
        $items = [];
        $score = 0;
        $maxScore = 30;
        $hasKeyword = !empty($keyword);

        // 1. Từ khóa trong thẻ tiêu đề con H2 / H3 (8đ)
        $headingsText = $this->extractHeadingsText($content);
        $hasHeadings = !empty($headingsText);
        $keywordInHeadings = $hasKeyword && $hasHeadings && $this->containsText($headingsText, $keyword);

        if ($keywordInHeadings) {
            $headScore = 8;
            $headStatus = 'pass';
            $headMsg = 'Rất tốt! Tiêu đề con (H2/H3) có chứa từ khóa chính.';
        } elseif ($hasHeadings) {
            $headScore = 4;
            $headStatus = 'warning';
            $headMsg = 'Đã có tiêu đề con (H2/H3), nhưng chưa chứa từ khóa chính.';
        } else {
            $headScore = 0;
            $headStatus = 'fail';
            $headMsg = 'Chưa sử dụng các thẻ tiêu đề con (H2, H3) trong bài viết/sản phẩm.';
        }
        $score += $headScore;
        $items[] = [
            'id' => 'keyword_in_headings',
            'label' => 'Từ khóa trong các thẻ tiêu đề con (H2, H3)',
            'passed' => $keywordInHeadings,
            'status' => $headStatus,
            'score' => $headScore,
            'max_score' => 8,
            'message' => $headMsg,
        ];

        // 2. Mật độ từ khóa trong bài (Keyword Density) (8đ)
        $density = $this->calculateKeywordDensity($content, $keyword);
        if ($density >= 1.0 && $density <= 2.5) {
            $denScore = 8;
            $denStatus = 'pass';
            $denMsg = "Mật độ từ khóa đạt {$density}% (Lý tưởng trong khoảng 1.0% - 2.5%).";
        } elseif ($density > 0 && ($density < 1.0 || $density <= 3.5)) {
            $denScore = 4;
            $denStatus = 'warning';
            $denMsg = $density < 1.0
                ? "Mật độ từ khóa là {$density}% (Hơi thấp, nên phân bổ thêm từ khóa tự nhiên)."
                : "Mật độ từ khóa là {$density}% (Hơi cao, tránh nhồi nhét từ khóa).";
        } else {
            $denScore = 0;
            $denStatus = 'fail';
            $denMsg = $density == 0
                ? "Từ khóa chính chưa xuất hiện trong nội dung bài viết."
                : "Mật độ từ khóa là {$density}% (Cảnh báo nhồi nhét từ khóa Spam Google!).";
        }
        $score += $denScore;
        $items[] = [
            'id' => 'keyword_density',
            'label' => 'Mật độ từ khóa chính (Keyword Density)',
            'passed' => $denScore >= 4,
            'status' => $denStatus,
            'score' => $denScore,
            'max_score' => 8,
            'message' => $denMsg,
        ];

        // 3. Hình ảnh đại diện & Hình ảnh trong nội dung kèm thuộc tính alt (8đ)
        $contentImages = $this->countImages($content);
        $imagesWithAlt = $this->countImagesWithAlt($content);

        if ($hasFeaturedImage && $contentImages > 0 && $imagesWithAlt >= $contentImages) {
            $imgScore = 8;
            $imgStatus = 'pass';
            $imgMsg = "Đầy đủ ảnh đại diện và {$contentImages} ảnh trong nội dung đều có thẻ Alt SEO.";
        } elseif ($hasFeaturedImage || $contentImages > 0) {
            $imgScore = 4;
            $imgStatus = 'warning';
            $imgMsg = "Đã có hình ảnh, nhưng nên thêm ảnh minh họa trong nội dung và điền thẻ Alt đầy đủ.";
        } else {
            $imgScore = 0;
            $imgStatus = 'fail';
            $imgMsg = "Chưa có hình ảnh đại diện hoặc hình ảnh minh họa trong nội dung.";
        }
        $score += $imgScore;
        $items[] = [
            'id' => 'images_and_alt',
            'label' => 'Tối ưu hình ảnh & Thẻ Alt (Image SEO)',
            'passed' => $imgScore >= 4,
            'status' => $imgStatus,
            'score' => $imgScore,
            'max_score' => 8,
            'message' => $imgMsg,
        ];

        // 4. Độ dài đường dẫn Slug (6đ)
        $slugLen = mb_strlen($slug, 'UTF-8');
        if ($slugLen >= 5 && $slugLen <= 75) {
            $slugScore = 6;
            $slugStatus = 'pass';
            $slugMsg = "Độ dài Slug hoàn hảo ({$slugLen} ký tự, <= 75 ký tự).";
        } elseif ($slugLen > 75) {
            $slugScore = 2;
            $slugStatus = 'warning';
            $slugMsg = "Đường dẫn Slug hơi dài ({$slugLen} ký tự), nên rút gọn dưới 75 ký tự.";
        } else {
            $slugScore = 0;
            $slugStatus = 'fail';
            $slugMsg = "Chưa có đường dẫn (Slug) hoặc đường dẫn quá ngắn.";
        }
        $score += $slugScore;
        $items[] = [
            'id' => 'slug_length',
            'label' => 'Độ dài URL / Slug thân thiện',
            'passed' => $slugScore >= 2,
            'status' => $slugStatus,
            'score' => $slugScore,
            'max_score' => 6,
            'message' => $slugMsg,
        ];

        return [
            'title' => 'Tối ưu Bổ sung',
            'score' => $score,
            'max_score' => $maxScore,
            'items' => $items,
        ];
    }

    /**
     * Đánh giá Khả năng đọc & Trình bày cho Bài viết & Sản phẩm (Tối đa 20 điểm).
     */
    protected function evaluateReadability(string $title, string $desc, string $content): array
    {
        $items = [];
        $score = 0;
        $maxScore = 20;

        // 1. Độ dài Tiêu đề chuẩn Google (7đ)
        $titleLen = mb_strlen($title, 'UTF-8');
        if ($titleLen >= 40 && $titleLen <= 65) {
            $tScore = 7;
            $tStatus = 'pass';
            $tMsg = "Độ dài tiêu đề hoàn hảo ({$titleLen}/60 ký tự), hiển thị trọn vẹn trên Google.";
        } elseif (($titleLen >= 25 && $titleLen < 40) || ($titleLen > 65 && $titleLen <= 75)) {
            $tScore = 4;
            $tStatus = 'warning';
            $tMsg = "Độ dài tiêu đề là {$titleLen} ký tự (Khuyến nghị chuẩn từ 40 - 60 ký tự).";
        } else {
            $tScore = 0;
            $tStatus = 'fail';
            $tMsg = "Tiêu đề {$titleLen} ký tự (Quá ngắn hoặc quá dài, dễ bị Google cắt xén).";
        }
        $score += $tScore;
        $items[] = [
            'id' => 'title_length',
            'label' => 'Độ dài tiêu đề (40 - 60 ký tự)',
            'passed' => $tScore >= 4,
            'status' => $tStatus,
            'score' => $tScore,
            'max_score' => 7,
            'message' => $tMsg,
        ];

        // 2. Độ dài Thẻ mô tả Meta Description (7đ)
        $descLen = mb_strlen($desc, 'UTF-8');
        if ($descLen >= 120 && $descLen <= 160) {
            $dScore = 7;
            $dStatus = 'pass';
            $dMsg = "Độ dài mô tả chuẩn xác ({$descLen}/160 ký tự), tối ưu hiển thị snippet.";
        } elseif (($descLen >= 70 && $descLen < 120) || ($descLen > 160 && $descLen <= 180)) {
            $dScore = 4;
            $dStatus = 'warning';
            $dMsg = "Độ dài mô tả là {$descLen} ký tự (Khuyến nghị tối ưu từ 120 - 160 ký tự).";
        } else {
            $dScore = 0;
            $dStatus = 'fail';
            $dMsg = "Độ dài mô tả là {$descLen} ký tự (Quá ngắn hoặc vượt quá giới hạn hiển thị).";
        }
        $score += $dScore;
        $items[] = [
            'id' => 'desc_length',
            'label' => 'Độ dài thẻ mô tả Meta (120 - 160 ký tự)',
            'passed' => $dScore >= 4,
            'status' => $dStatus,
            'score' => $dScore,
            'max_score' => 7,
            'message' => $dMsg,
        ];

        // 3. Phân đoạn nội dung bằng thẻ tiêu đề con (6đ)
        $headingsCount = $this->countHeadings($content);
        if ($headingsCount >= 2) {
            $cScore = 6;
            $cStatus = 'pass';
            $cMsg = "Nội dung được cấu trúc rõ ràng với {$headingsCount} thẻ tiêu đề con (H2/H3/H4).";
        } elseif ($headingsCount == 1) {
            $cScore = 3;
            $cStatus = 'warning';
            $cMsg = "Bài viết/sản phẩm mới có 1 thẻ tiêu đề con, nên bổ sung thêm để dễ đọc.";
        } else {
            $cScore = 0;
            $cStatus = 'fail';
            $cMsg = "Chưa có phân đoạn tiêu đề con (H2, H3), gây mỏi mắt cho người đọc.";
        }
        $score += $cScore;
        $items[] = [
            'id' => 'content_structure',
            'label' => 'Cấu trúc nội dung phân đoạn (Headings)',
            'passed' => $cScore >= 3,
            'status' => $cStatus,
            'score' => $cScore,
            'max_score' => 6,
            'message' => $cMsg,
        ];

        return [
            'title' => 'Khả năng Đọc & Trình Bày',
            'score' => $score,
            'max_score' => $maxScore,
            'items' => $items,
        ];
    }

    /**
     * Đánh giá Liên kết & Đa phương tiện cho Bài viết & Sản phẩm (Tối đa 10 điểm).
     */
    protected function evaluateLinks(string $content): array
    {
        $items = [];
        $score = 0;
        $maxScore = 10;

        // 1. Liên kết nội bộ (Internal Links) (5đ)
        $internalCount = $this->countInternalLinks($content);
        $hasInternal = $internalCount > 0;
        $inScore = $hasInternal ? 5 : 0;
        $score += $inScore;
        $items[] = [
            'id' => 'internal_links',
            'label' => 'Liên kết nội bộ (Internal Links)',
            'passed' => $hasInternal,
            'status' => $hasInternal ? 'pass' : 'fail',
            'score' => $inScore,
            'max_score' => 5,
            'message' => $hasInternal
                ? "Tốt! Có {$internalCount} liên kết nội bộ điều hướng người dùng."
                : 'Chưa có liên kết nội bộ. Hãy gắn link đến bài viết hoặc sản phẩm liên quan.',
        ];

        // 2. Liên kết ngoài (External / Outbound Links) (5đ)
        $externalCount = $this->countExternalLinks($content);
        $hasExternal = $externalCount > 0;
        $exScore = $hasExternal ? 5 : 0;
        $score += $exScore;
        $items[] = [
            'id' => 'external_links',
            'label' => 'Liên kết ngoài nguồn uy tín (External Links)',
            'passed' => $hasExternal,
            'status' => $hasExternal ? 'pass' : 'warning',
            'score' => $exScore,
            'max_score' => 5,
            'message' => $hasExternal
                ? "Tốt! Có {$externalCount} liên kết ngoài trích dẫn nguồn uy tín."
                : 'Nên có ít nhất 1 liên kết trích dẫn nguồn uy tín bên ngoài.',
        ];

        return [
            'title' => 'Liên kết & Tương tác',
            'score' => $score,
            'max_score' => $maxScore,
            'items' => $items,
        ];
    }

    /**
     * Tách lấy từ khóa mục tiêu chính đầu tiên.
     */
    public function extractPrimaryKeyword(string $keywords): string
    {
        if (empty($keywords)) {
            return '';
        }

        $parts = explode(',', $keywords);
        return trim($parts[0]);
    }

    /**
     * Kiểm tra chuỗi con không phân biệt dấu và chữ hoa thường.
     */
    public function containsText(string $haystack, string $needle): bool
    {
        if (empty($needle) || empty($haystack)) {
            return false;
        }

        $cleanHaystack = mb_strtolower($this->removeAccents($haystack), 'UTF-8');
        $cleanNeedle = mb_strtolower($this->removeAccents($needle), 'UTF-8');

        return str_contains($cleanHaystack, $cleanNeedle);
    }

    /**
     * Tính mật độ từ khóa trong văn bản (%).
     */
    public function calculateKeywordDensity(string $content, string $keyword): float
    {
        if (empty($keyword) || empty($content)) {
            return 0.0;
        }

        $plainText = strip_tags($content);
        $totalWords = $this->countWords($plainText);
        if ($totalWords === 0) {
            return 0.0;
        }

        $cleanContent = mb_strtolower($this->removeAccents($plainText), 'UTF-8');
        $cleanKeyword = mb_strtolower($this->removeAccents($keyword), 'UTF-8');

        $occurrences = substr_count($cleanContent, $cleanKeyword);
        $keywordWordsCount = max(1, count(preg_split('/\s+/u', trim($cleanKeyword))));

        $density = ($occurrences * $keywordWordsCount / $totalWords) * 100;
        return round($density, 2);
    }

    /**
     * Đếm số từ trong văn bản UTF-8 tiếng Việt.
     */
    public function countWords(string $html): int
    {
        $text = trim(strip_tags($html));
        if (empty($text)) {
            return 0;
        }

        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        return count($words);
    }

    /**
     * Trích xuất văn bản từ các thẻ Heading (H1-H6).
     */
    protected function extractHeadingsText(string $html): string
    {
        if (empty($html)) {
            return '';
        }

        preg_match_all('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $html, $matches);
        if (empty($matches[1])) {
            return '';
        }

        return implode(' ', array_map('strip_tags', $matches[1]));
    }

    /**
     * Đếm số lượng thẻ Headings (H2, H3, H4).
     */
    public function countHeadings(string $html): int
    {
        if (empty($html)) {
            return 0;
        }

        preg_match_all('/<h[2-4][^>]*>/i', $html, $matches);
        return count($matches[0]);
    }

    /**
     * Đếm số lượng hình ảnh trong nội dung.
     */
    public function countImages(string $html): int
    {
        if (empty($html)) {
            return 0;
        }

        preg_match_all('/<img[^>]+>/i', $html, $matches);
        return count($matches[0]);
    }

    /**
     * Đếm số lượng hình ảnh có thuộc tính alt hợp lệ.
     */
    public function countImagesWithAlt(string $html): int
    {
        if (empty($html)) {
            return 0;
        }

        preg_match_all('/<img[^>]+alt=[\'"]([^\'"]+)[\'"][^>]*>/i', $html, $matches);
        return count($matches[0]);
    }

    /**
     * Đếm số liên kết nội bộ.
     */
    public function countInternalLinks(string $html): int
    {
        if (empty($html)) {
            return 0;
        }

        $appHost = parse_url(config('app.url', url('/')), PHP_URL_HOST) ?? '';
        preg_match_all('/<a[^>]+href=[\'"]([^\'"]+)[\'"][^>]*>/i', $html, $matches);

        $count = 0;
        foreach ($matches[1] as $href) {
            $href = trim($href);
            if (empty($href) || str_starts_with($href, '#') || str_starts_with($href, 'javascript:')) {
                continue;
            }
            if (str_starts_with($href, '/') && !str_starts_with($href, '//')) {
                $count++;
            } elseif (!empty($appHost) && str_contains($href, $appHost)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Đếm số liên kết ngoài (External).
     */
    public function countExternalLinks(string $html): int
    {
        if (empty($html)) {
            return 0;
        }

        $appHost = parse_url(config('app.url', url('/')), PHP_URL_HOST) ?? '';
        preg_match_all('/<a[^>]+href=[\'"]([^\'"]+)[\'"][^>]*>/i', $html, $matches);

        $count = 0;
        foreach ($matches[1] as $href) {
            $href = trim($href);
            if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
                $host = parse_url($href, PHP_URL_HOST);
                if (!empty($host) && (!empty($appHost) ? !str_contains($host, $appHost) : true)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Bỏ dấu tiếng Việt để so sánh chuẩn xác.
     */
    public function removeAccents(string $str): string
    {
        $accents = [
            'a' => ['à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'],
            'e' => ['è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'],
            'i' => ['ì','í','ị','ỉ','ĩ'],
            'o' => ['ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'],
            'u' => ['ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'],
            'y' => ['ỳ','ý','ỵ','ỷ','ỹ'],
            'd' => ['đ'],
            'A' => ['À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ'],
            'E' => ['È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ'],
            'I' => ['Ì','Í','Ị','Ỉ','Ĩ'],
            'O' => ['Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ'],
            'U' => ['Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ'],
            'Y' => ['Ỳ','Ý','Ỵ','Ỷ','Ỹ'],
            'D' => ['Đ']
        ];

        foreach ($accents as $nonAccent => $accentList) {
            $str = str_replace($accentList, $nonAccent, $str);
        }

        return $str;
    }
}
