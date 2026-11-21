/**
 * SEO Real-Time Analyzer & Scoring Engine (Thang điểm 100 chuẩn WordPress Yoast & Rank Math)
 * ─────────────────────────────────────────────────────────────────────────────
 * - Hỗ trợ tự động cả 2 chế độ:
 *   1. Bài viết & Sản phẩm (Content Mode): Đầy đủ 4 nhóm tiêu chí gồm nội dung, headings, hình ảnh, liên kết
 *   2. Danh mục (Category/Taxonomy Mode): Đánh giá chuyên biệt tên danh mục, slug, từ khóa, mô tả meta & banner
 * - Tự động hook CKEditor 4 và các input/textarea trong form
 * - Khung xem trước Google SERP (Desktop & Mobile toggle)
 * - Đồng bộ giá trị điểm vào hidden input #seo_score_input để lưu CSDL
 * ─────────────────────────────────────────────────────────────────────────────
 */
(function (window, document) {
    'use strict';

    // Bỏ dấu tiếng Việt
    function removeAccents(str) {
        if (!str) return '';
        const accentsMap = [
            { base: 'a', letters: /[àáạảãâầấậẩẫăằắặẳẵ]/gi },
            { base: 'e', letters: /[èéẹẻẽêềếệểễ]/gi },
            { base: 'i', letters: /[ìíịỉĩ]/gi },
            { base: 'o', letters: /[òóọỏõôồốộổỗơờớợởỡ]/gi },
            { base: 'u', letters: /[ùúụủũưừứựửữ]/gi },
            { base: 'y', letters: /[ỳýỵỷỹ]/gi },
            { base: 'd', letters: /[đ]/gi },
            { base: 'D', letters: /[Đ]/g }
        ];
        let result = str;
        for (let i = 0; i < accentsMap.length; i++) {
            result = result.replace(accentsMap[i].letters, accentsMap[i].base);
        }
        return result;
    }

    // Slugify chuỗi
    function slugify(text) {
        if (!text) return '';
        return removeAccents(text)
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
    }

    // Đếm số từ tiếng Việt UTF-8
    function countWords(html) {
        if (!html) return 0;
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const text = (tmp.textContent || tmp.innerText || '').trim();
        if (!text) return 0;
        const words = text.match(/\S+/g);
        return words ? words.length : 0;
    }

    // Lấy nội dung CKEditor
    function getEditorContent() {
        if (typeof CKEDITOR !== 'undefined') {
            const possibleNames = ['content-vn', 'content_vn', 'content'];
            for (let name of possibleNames) {
                if (CKEDITOR.instances[name]) {
                    return CKEDITOR.instances[name].getData();
                }
            }
        }
        const textarea = document.getElementById('content-vn') ||
            document.querySelector('textarea[name="content_vn"]') ||
            document.querySelector('textarea[data-ckeditor="true"]');
        return textarea ? textarea.value : '';
    }

    // Thuật toán chấm điểm chuẩn SEO
    function computeSeoScore(params) {
        const title = (params.title || '').trim();
        const slug = (params.slug || '').trim();
        const rawKeywords = (params.keyword || '').trim();
        const desc = (params.description || '').trim();
        const intro = (params.intro || '').trim();
        const content = params.content || '';
        const hasFeaturedImg = !!params.hasImage;
        const isCategory = !!params.isCategory;

        // Lấy từ khóa chính đầu tiên
        const focusKeyword = (rawKeywords.split(',')[0] || '').trim();
        const cleanKeyword = removeAccents(focusKeyword).toLowerCase();
        const cleanTitle = removeAccents(title).toLowerCase();
        const cleanDesc = removeAccents(desc).toLowerCase();
        const cleanSlug = slug.toLowerCase();
        const keywordSlug = slugify(focusKeyword);

        const titleLen = title.length;
        const descLen = desc.length;
        const slugLen = slug.length;

        let totalScore = 0;
        const checklist = [];

        // ═══════════════════════════════════════════════════════════
        // CHẾ ĐỘ 1: PHÂN TÍCH DANH MỤC (CATEGORY MODE)
        // ═══════════════════════════════════════════════════════════
        if (isCategory) {
            let catBasic = 0;
            let catAdd = 0;
            let catRead = 0;

            // 1.1 Từ khóa trong Tiêu đề danh mục (15đ)
            const inTitle = cleanKeyword.length > 0 && cleanTitle.includes(cleanKeyword);
            const s1_1 = inTitle ? 15 : 0;
            catBasic += s1_1;
            checklist.push({
                group: 'basic',
                id: 'keyword_in_title',
                label: 'Từ khóa chính trong Tên danh mục',
                passed: inTitle,
                status: inTitle ? 'pass' : (cleanKeyword ? 'fail' : 'warning'),
                score: s1_1,
                max: 15,
                msg: inTitle ? 'Tên danh mục đã chứa từ khóa mục tiêu.' : 'Tên danh mục chưa chứa từ khóa mục tiêu.'
            });

            // 1.2 Từ khóa ở đầu tiêu đề (10đ)
            let atStart = false;
            if (inTitle) {
                const pos = cleanTitle.indexOf(cleanKeyword);
                atStart = (pos >= 0 && pos <= cleanTitle.length / 2);
            }
            const s1_2 = atStart ? 10 : (inTitle ? 5 : 0);
            catBasic += s1_2;
            checklist.push({
                group: 'basic',
                id: 'keyword_at_start_of_title',
                label: 'Từ khóa ở phần đầu Tên danh mục',
                passed: atStart,
                status: atStart ? 'pass' : (inTitle ? 'warning' : 'fail'),
                score: s1_2,
                max: 10,
                msg: atStart ? 'Rất tốt! Từ khóa nằm ở nửa đầu tiêu đề.' : 'Nên đặt từ khóa chính gần đầu tên danh mục.'
            });

            // 1.3 Từ khóa trong mô tả Meta (15đ)
            const inDesc = cleanKeyword.length > 0 && cleanDesc.includes(cleanKeyword);
            const s1_3 = inDesc ? 15 : 0;
            catBasic += s1_3;
            checklist.push({
                group: 'basic',
                id: 'keyword_in_meta_description',
                label: 'Từ khóa trong Thẻ mô tả Meta',
                passed: inDesc,
                status: inDesc ? 'pass' : (cleanKeyword ? 'fail' : 'warning'),
                score: s1_3,
                max: 15,
                msg: inDesc ? 'Thẻ mô tả danh mục đã chứa từ khóa chính.' : 'Thẻ mô tả danh mục chưa có từ khóa chính.'
            });

            // 2.1 Từ khóa trong Slug (15đ)
            const inSlug = keywordSlug.length > 0 && cleanSlug.includes(keywordSlug);
            const s2_1 = inSlug ? 15 : 0;
            catAdd += s2_1;
            checklist.push({
                group: 'additional',
                id: 'keyword_in_slug',
                label: 'Từ khóa trong Đường dẫn (Slug)',
                passed: inSlug,
                status: inSlug ? 'pass' : 'fail',
                score: s2_1,
                max: 15,
                msg: inSlug ? 'Đường dẫn danh mục chứa từ khóa chính không dấu.' : 'Đường dẫn slug chưa chứa từ khóa chính.'
            });

            // 2.2 Độ dài Slug thân thiện (10đ)
            const s2_2 = (slugLen >= 3 && slugLen <= 75) ? 10 : (slugLen > 75 ? 4 : 0);
            catAdd += s2_2;
            checklist.push({
                group: 'additional',
                id: 'slug_length',
                label: 'Độ dài URL / Slug thân thiện (<= 75 ký tự)',
                passed: s2_2 >= 4,
                status: s2_2 === 10 ? 'pass' : (s2_2 > 0 ? 'warning' : 'fail'),
                score: s2_2,
                max: 10,
                msg: s2_2 === 10 ? 'Độ dài slug (' + slugLen + ' ký tự) rất chuẩn.' : 'Slug quá dài hoặc chưa có slug.'
            });

            // 2.3 Hình ảnh / Banner danh mục (5đ)
            const s2_3 = hasFeaturedImg ? 5 : 0;
            catAdd += s2_3;
            checklist.push({
                group: 'additional',
                id: 'images_and_alt',
                label: 'Hình ảnh đại diện / Banner danh mục',
                passed: hasFeaturedImg,
                status: hasFeaturedImg ? 'pass' : 'warning',
                score: s2_3,
                max: 5,
                msg: hasFeaturedImg ? 'Danh mục đã có ảnh đại diện/banner trực quan.' : 'Nên tải lên ảnh đại diện hoặc banner cho danh mục.'
            });

            // 3.1 Độ dài Tiêu đề chuẩn (15đ)
            let s3_1 = 0;
            let s3_1Status = 'fail';
            let s3_1Msg = '';
            if (titleLen >= 30 && titleLen <= 65) {
                s3_1 = 15;
                s3_1Status = 'pass';
                s3_1Msg = 'Tiêu đề (' + titleLen + ' ký tự) hiển thị trọn vẹn trên Google SERP.';
            } else if (titleLen >= 20 && titleLen <= 75) {
                s3_1 = 8;
                s3_1Status = 'warning';
                s3_1Msg = 'Tiêu đề (' + titleLen + ' ký tự) hơi ngắn hoặc hơi dài (khuyên dùng 30-65 ký tự).';
            } else {
                s3_1 = 0;
                s3_1Status = 'fail';
                s3_1Msg = 'Tiêu đề (' + titleLen + ' ký tự) quá ngắn hoặc quá dài.';
            }
            catRead += s3_1;
            checklist.push({
                group: 'readability',
                id: 'title_length',
                label: 'Độ dài tiêu đề danh mục (30 - 65 ký tự)',
                passed: s3_1 >= 8,
                status: s3_1Status,
                score: s3_1,
                max: 15,
                msg: s3_1Msg
            });

            // 3.2 Độ dài Mô tả Meta chuẩn (15đ)
            let s3_2 = 0;
            let s3_2Status = 'fail';
            let s3_2Msg = '';
            if (descLen >= 100 && descLen <= 160) {
                s3_2 = 15;
                s3_2Status = 'pass';
                s3_2Msg = 'Mô tả danh mục (' + descLen + '/160 ký tự) chuẩn xác.';
            } else if ((descLen >= 50 && descLen < 100) || (descLen > 160 && descLen <= 180)) {
                s3_2 = 8;
                s3_2Status = 'warning';
                s3_2Msg = 'Mô tả (' + descLen + ' ký tự) nên điều chỉnh về khoảng 100 - 160 ký tự.';
            } else {
                s3_2 = 0;
                s3_2Status = 'fail';
                s3_2Msg = 'Mô tả danh mục (' + descLen + ' ký tự) chưa đạt chuẩn độ dài.';
            }
            catRead += s3_2;
            checklist.push({
                group: 'readability',
                id: 'desc_length',
                label: 'Độ dài thẻ mô tả Meta (100 - 160 ký tự)',
                passed: s3_2 >= 8,
                status: s3_2Status,
                score: s3_2,
                max: 15,
                msg: s3_2Msg
            });

            totalScore = Math.max(0, Math.min(100, Math.round(catBasic + catAdd + catRead)));

            let rating = 'bad';
            let ratingText = 'Cần cải thiện (Kém)';
            let badgeClass = 'danger';
            let strokeColor = '#ef4444';

            if (totalScore >= 80) {
                rating = 'good';
                ratingText = 'Tốt / Chuẩn SEO';
                badgeClass = 'success';
                strokeColor = '#10b981';
            } else if (totalScore >= 50) {
                rating = 'ok';
                ratingText = 'Khá / Cần tối ưu';
                badgeClass = 'warning';
                strokeColor = '#f59e0b';
            }

            return {
                totalScore: totalScore,
                rating: rating,
                ratingText: ratingText,
                badgeClass: badgeClass,
                strokeColor: strokeColor,
                focusKeyword: focusKeyword,
                isCategory: true,
                scores: {
                    basic: catBasic,
                    additional: catAdd,
                    readability: catRead,
                    links: 0
                },
                metrics: {
                    words: 0,
                    density: 0,
                    titleLen: titleLen,
                    descLen: descLen,
                    headings: 0,
                    images: hasFeaturedImg ? 1 : 0,
                    internalLinks: 0,
                    externalLinks: 0
                },
                checklist: checklist
            };
        }

        // ═══════════════════════════════════════════════════════════
        // CHẾ ĐỘ 2: BÀI VIẾT & SẢN PHẨM (CONTENT MODE)
        // ═══════════════════════════════════════════════════════════
        const tmpDiv = document.createElement('div');
        tmpDiv.innerHTML = content;
        const plainContent = (tmpDiv.textContent || tmpDiv.innerText || '').trim();
        const cleanContent = removeAccents(plainContent).toLowerCase();
        const totalWords = countWords(content);

        // 1. NHÓM TIÊU CHÍ: SEO CƠ BẢN (Tối đa 40 điểm)
        let basicScore = 0;

        // 1.1 Từ khóa chính trong tiêu đề (10đ)
        const inTitle = cleanKeyword.length > 0 && cleanTitle.includes(cleanKeyword);
        const s1_1 = inTitle ? 10 : 0;
        basicScore += s1_1;
        checklist.push({
            group: 'basic',
            id: 'keyword_in_title',
            label: 'Từ khóa chính trong Tiêu đề SEO',
            passed: inTitle,
            status: inTitle ? 'pass' : (cleanKeyword ? 'fail' : 'warning'),
            score: s1_1,
            max: 10,
            msg: inTitle
                ? 'Tuyệt vời! Tiêu đề bài viết đã chứa từ khóa chính.'
                : (cleanKeyword ? 'Tiêu đề chưa chứa từ khóa "' + focusKeyword + '".' : 'Chưa nhập từ khóa SEO mục tiêu.')
        });

        // 1.2 Từ khóa ở đầu tiêu đề (5đ)
        let atStart = false;
        if (inTitle) {
            const pos = cleanTitle.indexOf(cleanKeyword);
            atStart = (pos >= 0 && pos <= cleanTitle.length / 2);
        }
        const s1_2 = atStart ? 5 : (inTitle ? 2 : 0);
        basicScore += s1_2;
        checklist.push({
            group: 'basic',
            id: 'keyword_at_start_of_title',
            label: 'Từ khóa xuất hiện ở phần đầu Tiêu đề',
            passed: atStart,
            status: atStart ? 'pass' : (inTitle ? 'warning' : 'fail'),
            score: s1_2,
            max: 5,
            msg: atStart
                ? 'Rất tốt! Từ khóa chính nằm ở nửa đầu tiêu đề.'
                : 'Nên đặt từ khóa chính gần đầu tiêu đề để tăng tỷ lệ nhấp CTR.'
        });

        // 1.3 Từ khóa trong Thẻ mô tả Meta (8đ)
        const inDesc = cleanKeyword.length > 0 && cleanDesc.includes(cleanKeyword);
        const s1_3 = inDesc ? 8 : 0;
        basicScore += s1_3;
        checklist.push({
            group: 'basic',
            id: 'keyword_in_meta_description',
            label: 'Từ khóa chính trong Thẻ mô tả Meta',
            passed: inDesc,
            status: inDesc ? 'pass' : (cleanKeyword ? 'fail' : 'warning'),
            score: s1_3,
            max: 8,
            msg: inDesc
                ? 'Chính xác! Thẻ mô tả Meta đã xuất hiện từ khóa chính.'
                : 'Thẻ mô tả Meta chưa chứa từ khóa chính.'
        });

        // 1.4 Từ khóa trong Đường dẫn Slug (7đ)
        const inSlug = keywordSlug.length > 0 && cleanSlug.includes(keywordSlug);
        const s1_4 = inSlug ? 7 : 0;
        basicScore += s1_4;
        checklist.push({
            group: 'basic',
            id: 'keyword_in_slug',
            label: 'Từ khóa chính trong Đường dẫn (Slug)',
            passed: inSlug,
            status: inSlug ? 'pass' : 'fail',
            score: s1_4,
            max: 7,
            msg: inSlug
                ? 'Chuẩn SEO! Đường dẫn URL chứa từ khóa chính thân thiện.'
                : 'Đường dẫn (slug) chưa chứa từ khóa chính không dấu.'
        });

        // 1.5 Từ khóa trong đoạn mở đầu / Sapo (5đ)
        const earlyText = removeAccents(intro + ' ' + plainContent.substring(0, 400)).toLowerCase();
        const inEarly = cleanKeyword.length > 0 && earlyText.includes(cleanKeyword);
        const s1_5 = inEarly ? 5 : 0;
        basicScore += s1_5;
        checklist.push({
            group: 'basic',
            id: 'keyword_in_intro',
            label: 'Từ khóa trong đoạn mở đầu (10% đầu bài)',
            passed: inEarly,
            status: inEarly ? 'pass' : 'fail',
            score: s1_5,
            max: 5,
            msg: inEarly
                ? 'Xuất sắc! Từ khóa xuất hiện ngay trong phần giới thiệu.'
                : 'Hãy nhắc đến từ khóa chính trong đoạn mở đầu / Sapo.'
        });

        // 1.6 Độ dài bài viết tối thiểu (5đ)
        let s1_6 = 0;
        let s1_6Status = 'fail';
        let s1_6Msg = '';
        if (totalWords >= 600) {
            s1_6 = 5;
            s1_6Status = 'pass';
            s1_6Msg = 'Độ dài bài viết đạt ' + totalWords + ' từ (Chuẩn khuyến nghị >= 600 từ).';
        } else if (totalWords >= 300) {
            s1_6 = 3;
            s1_6Status = 'warning';
            s1_6Msg = 'Bài viết có ' + totalWords + ' từ (Khá, nên bổ sung lên trên 600 từ).';
        } else {
            s1_6 = 0;
            s1_6Status = 'fail';
            s1_6Msg = 'Nội dung có ' + totalWords + ' từ (Quá ngắn, chuẩn SEO cần ít nhất 300 - 600 từ).';
        }
        basicScore += s1_6;
        checklist.push({
            group: 'basic',
            id: 'content_length',
            label: 'Độ dài bài viết tối thiểu',
            passed: s1_6 >= 3,
            status: s1_6Status,
            score: s1_6,
            max: 5,
            msg: s1_6Msg
        });

        // 2. NHÓM TIÊU CHÍ: TỐI ƯU BỔ SUNG (Tối đa 30 điểm)
        let additionalScore = 0;

        // 2.1 Từ khóa trong thẻ tiêu đề con H2/H3 (8đ)
        const headings = tmpDiv.querySelectorAll('h2, h3, h4');
        const hasHeadings = headings.length > 0;
        let headingHasKeyword = false;
        if (cleanKeyword.length > 0 && hasHeadings) {
            for (let i = 0; i < headings.length; i++) {
                if (removeAccents(headings[i].textContent).toLowerCase().includes(cleanKeyword)) {
                    headingHasKeyword = true;
                    break;
                }
            }
        }
        let s2_1 = headingHasKeyword ? 8 : (hasHeadings ? 4 : 0);
        let s2_1Status = headingHasKeyword ? 'pass' : (hasHeadings ? 'warning' : 'fail');
        additionalScore += s2_1;
        checklist.push({
            group: 'additional',
            id: 'keyword_in_headings',
            label: 'Từ khóa trong thẻ tiêu đề con (H2, H3)',
            passed: headingHasKeyword,
            status: s2_1Status,
            score: s2_1,
            max: 8,
            msg: headingHasKeyword
                ? 'Rất tốt! Thẻ tiêu đề con (H2/H3) có chứa từ khóa chính.'
                : (hasHeadings ? 'Đã có tiêu đề con, nhưng chưa chứa từ khóa chính.' : 'Chưa sử dụng các thẻ tiêu đề con H2, H3 trong bài.')
        });

        // 2.2 Mật độ từ khóa chính (Keyword Density) (8đ)
        let keywordCount = 0;
        if (cleanKeyword.length > 0 && cleanContent.length > 0) {
            let escaped = cleanKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            let regex = new RegExp(escaped, 'g');
            let matches = cleanContent.match(regex);
            keywordCount = matches ? matches.length : 0;
        }
        const kwWords = cleanKeyword ? cleanKeyword.split(/\s+/).length : 1;
        const density = totalWords > 0 ? ((keywordCount * kwWords) / totalWords) * 100 : 0;
        const roundedDensity = Math.round(density * 100) / 100;

        let s2_2 = 0;
        let s2_2Status = 'fail';
        let s2_2Msg = '';
        if (roundedDensity >= 1.0 && roundedDensity <= 2.5) {
            s2_2 = 8;
            s2_2Status = 'pass';
            s2_2Msg = 'Mật độ từ khóa đạt ' + roundedDensity + '% (Lý tưởng 1.0% - 2.5%).';
        } else if (roundedDensity > 0 && (roundedDensity < 1.0 || roundedDensity <= 3.5)) {
            s2_2 = 4;
            s2_2Status = 'warning';
            s2_2Msg = roundedDensity < 1.0
                ? 'Mật độ từ khóa là ' + roundedDensity + '% (Hơi thấp, nên phân bổ thêm tự nhiên).'
                : 'Mật độ từ khóa là ' + roundedDensity + '% (Hơi cao, tránh lặp từ quá nhiều).';
        } else {
            s2_2 = 0;
            s2_2Status = 'fail';
            s2_2Msg = roundedDensity === 0
                ? 'Từ khóa chính chưa xuất hiện trong nội dung bài viết.'
                : 'Mật độ từ khóa là ' + roundedDensity + '% (Cảnh báo nhồi nhét từ khóa Spam!).';
        }
        additionalScore += s2_2;
        checklist.push({
            group: 'additional',
            id: 'keyword_density',
            label: 'Mật độ từ khóa chính (Keyword Density)',
            passed: s2_2 >= 4,
            status: s2_2Status,
            score: s2_2,
            max: 8,
            msg: s2_2Msg
        });

        // 2.3 Hình ảnh và Thẻ Alt ảnh (8đ)
        const contentImgs = tmpDiv.querySelectorAll('img');
        let imgWithAlt = 0;
        contentImgs.forEach(function (img) {
            if (img.getAttribute('alt') && img.getAttribute('alt').trim().length > 0) {
                imgWithAlt++;
            }
        });

        let s2_3 = 0;
        let s2_3Status = 'fail';
        let s2_3Msg = '';
        if (hasFeaturedImg && contentImgs.length > 0 && imgWithAlt >= contentImgs.length) {
            s2_3 = 8;
            s2_3Status = 'pass';
            s2_3Msg = 'Đầy đủ ảnh đại diện và ' + contentImgs.length + ' ảnh trong bài đều có thẻ Alt.';
        } else if (hasFeaturedImg || contentImgs.length > 0) {
            s2_3 = 4;
            s2_3Status = 'warning';
            s2_3Msg = 'Đã có hình ảnh, hãy bổ sung ảnh minh họa nội dung kèm thẻ Alt đầy đủ.';
        } else {
            s2_3 = 0;
            s2_3Status = 'fail';
            s2_3Msg = 'Bài viết chưa có hình ảnh đại diện hoặc hình minh họa trong bài.';
        }
        additionalScore += s2_3;
        checklist.push({
            group: 'additional',
            id: 'images_and_alt',
            label: 'Tối ưu hình ảnh & Thẻ Alt (Image SEO)',
            passed: s2_3 >= 4,
            status: s2_3Status,
            score: s2_3,
            max: 8,
            msg: s2_3Msg
        });

        // 2.4 Độ dài đường dẫn Slug (6đ)
        let s2_4 = 0;
        let s2_4Status = 'fail';
        let s2_4Msg = '';
        if (slugLen >= 5 && slugLen <= 75) {
            s2_4 = 6;
            s2_4Status = 'pass';
            s2_4Msg = 'Độ dài Slug hoàn hảo (' + slugLen + ' ký tự, <= 75 ký tự).';
        } else if (slugLen > 75) {
            s2_4 = 2;
            s2_4Status = 'warning';
            s2_4Msg = 'Đường dẫn Slug hơi dài (' + slugLen + ' ký tự), nên rút gọn dưới 75 ký tự.';
        } else {
            s2_4 = 0;
            s2_4Status = 'fail';
            s2_4Msg = 'Chưa có đường dẫn (Slug) hoặc đường dẫn quá ngắn.';
        }
        additionalScore += s2_4;
        checklist.push({
            group: 'additional',
            id: 'slug_length',
            label: 'Độ dài URL / Slug thân thiện',
            passed: s2_4 >= 2,
            status: s2_4Status,
            score: s2_4,
            max: 6,
            msg: s2_4Msg
        });

        // 3. NHÓM TIÊU CHÍ: KHẢ NĂNG ĐỌC & TRÌNH BÀY (Tối đa 20 điểm)
        let readabilityScore = 0;

        // 3.1 Độ dài Tiêu đề chuẩn Google (7đ)
        let s3_1 = 0;
        let s3_1Status = 'fail';
        let s3_1Msg = '';
        if (titleLen >= 40 && titleLen <= 65) {
            s3_1 = 7;
            s3_1Status = 'pass';
            s3_1Msg = 'Độ dài tiêu đề hoàn hảo (' + titleLen + '/60 ký tự), hiển thị trọn vẹn.';
        } else if ((titleLen >= 25 && titleLen < 40) || (titleLen > 65 && titleLen <= 75)) {
            s3_1 = 4;
            s3_1Status = 'warning';
            s3_1Msg = 'Độ dài tiêu đề là ' + titleLen + ' ký tự (Khuyến nghị chuẩn 40 - 60 ký tự).';
        } else {
            s3_1 = 0;
            s3_1Status = 'fail';
            s3_1Msg = 'Tiêu đề ' + titleLen + ' ký tự (Quá ngắn hoặc quá dài, dễ bị cắt xén).';
        }
        readabilityScore += s3_1;
        checklist.push({
            group: 'readability',
            id: 'title_length',
            label: 'Độ dài tiêu đề bài viết (40 - 60 ký tự)',
            passed: s3_1 >= 4,
            status: s3_1Status,
            score: s3_1,
            max: 7,
            msg: s3_1Msg
        });

        // 3.2 Độ dài thẻ mô tả Meta (7đ)
        let s3_2 = 0;
        let s3_2Status = 'fail';
        let s3_2Msg = '';
        if (descLen >= 120 && descLen <= 160) {
            s3_2 = 7;
            s3_2Status = 'pass';
            s3_2Msg = 'Độ dài mô tả chuẩn xác (' + descLen + '/160 ký tự), tối ưu snippet Google.';
        } else if ((descLen >= 70 && descLen < 120) || (descLen > 160 && descLen <= 180)) {
            s3_2 = 4;
            s3_2Status = 'warning';
            s3_2Msg = 'Độ dài mô tả là ' + descLen + ' ký tự (Khuyến nghị chuẩn 120 - 160 ký tự).';
        } else {
            s3_2 = 0;
            s3_2Status = 'fail';
            s3_2Msg = 'Độ dài mô tả là ' + descLen + ' ký tự (Quá ngắn hoặc vượt giới hạn 160 ký tự).';
        }
        readabilityScore += s3_2;
        checklist.push({
            group: 'readability',
            id: 'desc_length',
            label: 'Độ dài thẻ mô tả Meta (120 - 160 ký tự)',
            passed: s3_2 >= 4,
            status: s3_2Status,
            score: s3_2,
            max: 7,
            msg: s3_2Msg
        });

        // 3.3 Cấu trúc nội dung phân đoạn (6đ)
        let s3_3 = 0;
        let s3_3Status = 'fail';
        let s3_3Msg = '';
        if (headings.length >= 2) {
            s3_3 = 6;
            s3_3Status = 'pass';
            s3_3Msg = 'Nội dung phân đoạn rõ ràng với ' + headings.length + ' thẻ tiêu đề con (H2/H3).';
        } else if (headings.length === 1) {
            s3_3 = 3;
            s3_3Status = 'warning';
            s3_3Msg = 'Bài viết mới có 1 thẻ tiêu đề con, nên bổ sung thêm để tăng trải nghiệm đọc.';
        } else {
            s3_3 = 0;
            s3_3Status = 'fail';
            s3_3Msg = 'Bài viết chưa có phân đoạn tiêu đề con (H2, H3), gây mỏi mắt cho người đọc.';
        }
        readabilityScore += s3_3;
        checklist.push({
            group: 'readability',
            id: 'content_structure',
            label: 'Cấu trúc nội dung phân đoạn (Headings)',
            passed: s3_3 >= 3,
            status: s3_3Status,
            score: s3_3,
            max: 6,
            msg: s3_3Msg
        });

        // 4. NHÓM TIÊU CHÍ: LIÊN KẾT & TƯƠNG TÁC (Tối đa 10 điểm)
        let linkScore = 0;
        const links = tmpDiv.querySelectorAll('a[href]');
        let internalLinks = 0;
        let externalLinks = 0;
        const currentHost = window.location.hostname;

        links.forEach(function (a) {
            const href = (a.getAttribute('href') || '').trim();
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (href.startsWith('/') && !href.startsWith('//')) {
                internalLinks++;
            } else if (href.startsWith('http://') || href.startsWith('https://')) {
                try {
                    const url = new URL(href);
                    if (url.hostname === currentHost || url.hostname.endsWith('.' + currentHost)) {
                        internalLinks++;
                    } else {
                        externalLinks++;
                    }
                } catch (e) {
                    externalLinks++;
                }
            }
        });

        // 4.1 Liên kết nội bộ (5đ)
        const hasInternal = internalLinks > 0;
        const s4_1 = hasInternal ? 5 : 0;
        linkScore += s4_1;
        checklist.push({
            group: 'links',
            id: 'internal_links',
            label: 'Liên kết nội bộ (Internal Links)',
            passed: hasInternal,
            status: hasInternal ? 'pass' : 'fail',
            score: s4_1,
            max: 5,
            msg: hasInternal
                ? 'Tốt! Có ' + internalLinks + ' liên kết nội bộ điều hướng người dùng.'
                : 'Chưa có liên kết nội bộ. Hãy gắn link đến bài viết hoặc sản phẩm liên quan.'
        });

        // 4.2 Liên kết ngoài (5đ)
        const hasExternal = externalLinks > 0;
        const s4_2 = hasExternal ? 5 : 0;
        linkScore += s4_2;
        checklist.push({
            group: 'links',
            id: 'external_links',
            label: 'Liên kết ngoài nguồn uy tín (External Links)',
            passed: hasExternal,
            status: hasExternal ? 'pass' : 'warning',
            score: s4_2,
            max: 5,
            msg: hasExternal
                ? 'Tốt! Có ' + externalLinks + ' liên kết ngoài trích dẫn nguồn uy tín.'
                : 'Nên có ít nhất 1 liên kết trích dẫn nguồn uy tín bên ngoài.'
        });

        totalScore = Math.max(0, Math.min(100, Math.round(basicScore + additionalScore + readabilityScore + linkScore)));

        let rating = 'bad';
        let ratingText = 'Cần cải thiện (Kém)';
        let badgeClass = 'danger';
        let strokeColor = '#ef4444';

        if (totalScore >= 80) {
            rating = 'good';
            ratingText = 'Tốt / Chuẩn SEO';
            badgeClass = 'success';
            strokeColor = '#10b981';
        } else if (totalScore >= 50) {
            rating = 'ok';
            ratingText = 'Khá / Cần tối ưu';
            badgeClass = 'warning';
            strokeColor = '#f59e0b';
        }

        return {
            totalScore: totalScore,
            rating: rating,
            ratingText: ratingText,
            badgeClass: badgeClass,
            strokeColor: strokeColor,
            focusKeyword: focusKeyword,
            isCategory: false,
            scores: {
                basic: basicScore,
                additional: additionalScore,
                readability: readabilityScore,
                links: linkScore
            },
            metrics: {
                words: totalWords,
                density: roundedDensity,
                titleLen: titleLen,
                descLen: descLen,
                headings: headings.length,
                images: contentImgs.length,
                internalLinks: internalLinks,
                externalLinks: externalLinks
            },
            checklist: checklist
        };
    }

    // Cập nhật giao diện Widget Đánh giá SEO
    function updateSeoUI(result) {
        // 1. Cập nhật Điểm số & Vòng tròn / Progress
        const scoreDisplay = document.getElementById('seo-score-display');
        const scoreRating = document.getElementById('seo-score-rating');
        const scoreProgress = document.getElementById('seo-score-progress');
        const scoreInput = document.getElementById('seo_score_input');
        const scoreCircle = document.getElementById('seo-score-circle');

        if (scoreDisplay) scoreDisplay.textContent = result.totalScore;
        if (scoreInput) scoreInput.value = result.totalScore;

        if (scoreRating) {
            scoreRating.textContent = result.ratingText;
            scoreRating.className = 'badge bg-' + result.badgeClass + '-subtle text-' + result.badgeClass + ' border border-' + result.badgeClass + '-subtle';
        }

        if (scoreProgress) {
            scoreProgress.style.width = result.totalScore + '%';
            scoreProgress.className = 'progress-bar bg-' + result.badgeClass;
        }

        if (scoreCircle) {
            const circumference = 2 * Math.PI * 36; // r=36 -> 226.2
            const offset = circumference - (result.totalScore / 100) * circumference;
            scoreCircle.style.strokeDashoffset = offset;
            scoreCircle.style.stroke = result.strokeColor;
        }

        // 2. Thống kê nhanh
        const totalItems = result.checklist.length;
        const passedItems = result.checklist.filter(function (it) { return it.passed; }).length;
        const summaryBadge = document.getElementById('seo-passed-summary');
        if (summaryBadge) {
            summaryBadge.textContent = passedItems + '/' + totalItems + ' đạt';
            summaryBadge.className = 'badge bg-' + (passedItems >= (result.isCategory ? 6 : 10) ? 'success' : (passedItems >= 4 ? 'warning' : 'danger'));
        }

        // Ẩn/hiện các mục không áp dụng cho danh mục
        const linksAccordion = document.getElementById('headingLinks');
        if (linksAccordion && linksAccordion.closest('.accordion-item')) {
            linksAccordion.closest('.accordion-item').style.display = result.isCategory ? 'none' : 'block';
        }

        const postOnlyItems = ['seo-item-keyword_in_intro', 'seo-item-content_length', 'seo-item-keyword_in_headings', 'seo-item-keyword_density', 'seo-item-content_structure'];
        postOnlyItems.forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = result.isCategory ? 'none' : 'flex';
            }
        });

        // 3. Cập nhật Checklist từng mục
        result.checklist.forEach(function (item) {
            const rowElem = document.getElementById('seo-item-' + item.id);
            if (!rowElem) return;

            const iconElem = rowElem.querySelector('.seo-item-icon');
            const scoreElem = rowElem.querySelector('.seo-item-score');
            const msgElem = rowElem.querySelector('.seo-item-msg');

            if (iconElem) {
                if (item.status === 'pass') {
                    iconElem.className = 'seo-item-icon ri-checkbox-circle-fill text-success fs-16 me-2';
                } else if (item.status === 'warning') {
                    iconElem.className = 'seo-item-icon ri-alert-fill text-warning fs-16 me-2';
                } else {
                    iconElem.className = 'seo-item-icon ri-close-circle-fill text-danger fs-16 me-2';
                }
            }

            if (scoreElem) {
                scoreElem.textContent = item.score + '/' + item.max + 'đ';
                scoreElem.className = 'seo-item-score badge bg-' + (item.passed ? 'success-subtle text-success' : 'light text-muted') + ' fs-11';
            }

            if (msgElem) {
                msgElem.textContent = item.msg;
            }
        });

        // 4. Cập nhật nhóm điểm
        const catBasic = document.getElementById('seo-cat-basic-score');
        const catAdd = document.getElementById('seo-cat-additional-score');
        const catRead = document.getElementById('seo-cat-readability-score');
        const catLinks = document.getElementById('seo-cat-links-score');

        if (catBasic) catBasic.textContent = result.scores.basic + '/40đ';
        if (catAdd) catAdd.textContent = result.scores.additional + '/30đ';
        if (catRead) catRead.textContent = result.scores.readability + (result.isCategory ? '/30đ' : '/20đ');
        if (catLinks) catLinks.textContent = result.scores.links + '/10đ';
    }

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                func.apply(context, args);
            }, wait);
        };
    }

    // Hàm thực hiện phân tích
    function runAnalysis() {
        const titleInput = document.getElementById('name_vn') || document.querySelector('input[name="name_vn"]');
        const slugInput = document.getElementById('slug-input') || document.getElementById('slug_vn') || document.querySelector('input[name="slug_vn"]');
        const keywordInput = document.getElementById('keyword_vn') || document.querySelector('textarea[name="keyword_vn"]');
        const descInput = document.getElementById('seo_description_input') || document.querySelector('textarea[name="description_vn"]');
        const introInput = document.getElementById('intro_vn') || document.querySelector('textarea[name="intro_vn"]');
        const imgInput = document.querySelector('input[name="image_vn"]');
        const existingImg = document.querySelector('img[src*="uploads/"]');

        const editorTextarea = document.getElementById('content-vn') || document.querySelector('textarea[name="content_vn"]');
        const isCategory = !editorTextarea && !introInput;

        const params = {
            title: titleInput ? titleInput.value : '',
            slug: slugInput ? slugInput.value : '',
            keyword: keywordInput ? keywordInput.value : '',
            description: descInput ? descInput.value : '',
            intro: introInput ? introInput.value : '',
            content: getEditorContent(),
            hasImage: (imgInput && imgInput.files && imgInput.files.length > 0) || !!existingImg,
            isCategory: isCategory
        };

        const result = computeSeoScore(params);
        updateSeoUI(result);
    }

    const debouncedRun = debounce(runAnalysis, 250);

    // Khởi tạo các event listeners khi DOM sẵn sàng
    function init() {
        const titleInput = document.getElementById('name_vn') || document.querySelector('input[name="name_vn"]');
        const slugInput = document.getElementById('slug-input') || document.getElementById('slug_vn') || document.querySelector('input[name="slug_vn"]');
        const keywordInput = document.getElementById('keyword_vn') || document.querySelector('textarea[name="keyword_vn"]');
        const descInput = document.getElementById('seo_description_input') || document.querySelector('textarea[name="description_vn"]');
        const introInput = document.getElementById('intro_vn') || document.querySelector('textarea[name="intro_vn"]');

        if (titleInput) titleInput.addEventListener('input', debouncedRun);
        if (slugInput) slugInput.addEventListener('input', debouncedRun);
        if (keywordInput) keywordInput.addEventListener('input', debouncedRun);
        if (descInput) descInput.addEventListener('input', debouncedRun);
        if (introInput) introInput.addEventListener('input', debouncedRun);

        // Lắng nghe sự kiện CKEditor
        function hookCKEditor() {
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances) {
                for (let instanceName in CKEDITOR.instances) {
                    const inst = CKEDITOR.instances[instanceName];
                    if (inst && !inst._seoHooked) {
                        inst._seoHooked = true;
                        inst.on('change', debouncedRun);
                        inst.on('blur', debouncedRun);
                        inst.on('key', debouncedRun);
                    }
                }
            }
        }

        // Kiểm tra CKEditor định kỳ để hook ngay khi editor nạp xong
        hookCKEditor();
        const ckInterval = setInterval(function () {
            hookCKEditor();
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['content-vn']) {
                clearInterval(ckInterval);
                runAnalysis();
            }
        }, 500);

        // Hủy interval sau 10 giây nếu CKEditor không có
        setTimeout(function () { clearInterval(ckInterval); }, 10000);

        // SERP Desktop vs Mobile view toggle
        const btnDesktop = document.getElementById('serp-view-desktop');
        const btnMobile = document.getElementById('serp-view-mobile');
        const serpContainer = document.getElementById('serp-preview-container');

        if (btnDesktop && btnMobile && serpContainer) {
            btnDesktop.addEventListener('click', function () {
                btnDesktop.classList.add('active', 'btn-primary');
                btnDesktop.classList.remove('btn-outline-secondary');
                btnMobile.classList.remove('active', 'btn-primary');
                btnMobile.classList.add('btn-outline-secondary');
                serpContainer.style.maxWidth = '100%';
            });

            btnMobile.addEventListener('click', function () {
                btnMobile.classList.add('active', 'btn-primary');
                btnMobile.classList.remove('btn-outline-secondary');
                btnDesktop.classList.remove('active', 'btn-primary');
                btnDesktop.classList.add('btn-outline-secondary');
                serpContainer.style.maxWidth = '375px';
            });
        }

        // Nút phân tích lại SEO (manual refresh)
        const btnRefresh = document.getElementById('btn-reanalyze-seo');
        if (btnRefresh) {
            btnRefresh.addEventListener('click', function (e) {
                e.preventDefault();
                runAnalysis();
            });
        }

        // Chạy lần đầu
        runAnalysis();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Export ra window để có thể gọi thủ công khi cần
    window.SeoAnalyzer = {
        analyze: computeSeoScore,
        run: runAnalysis
    };

})(window, document);
