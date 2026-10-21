<?php

// Gọi chủ đề con của chủ đề con, nhớ phải có tên id của category ở biến cuối

if (! function_exists('renderCategoryOptions')) {
    function renderCategoryOptions($categories, $level = 0, $selectedParentId = null, $idAttribute = 'id_category_product')
    {
        if (empty($categories) || (!is_array($categories) && !is_object($categories))) {
            return;
        }

        foreach ($categories as $item) {
            if (!$item) {
                continue;
            }
            $prefix = str_repeat('|---', $level);
            $categoryId = $item->{$idAttribute} ?? null;
            
            $style = ($level == 0) ? ' style="font-weight: bold;"' : '';
            
            echo '<option value="'.$categoryId.'"'.(($selectedParentId == $categoryId) ? ' selected' : '').$style.'>'.$prefix.($item->name_vn ?? '').'</option>';

            if (! empty($item->children) && (is_array($item->children) || is_object($item->children))) {
                renderCategoryOptions($item->children, $level + 1, $selectedParentId, $idAttribute);
            }
        }
    }
}