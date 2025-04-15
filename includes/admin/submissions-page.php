<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="tablenav top">
        <div class="alignleft actions">
            <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=rorkpop_export_csv&nonce=' . wp_create_nonce('rorkpop-admin-nonce'))); ?>" class="button">Export as CSV</a>
        </div>
        <div class="tablenav-pages">
            <?php if ($total_pages > 1) : ?>
                <span class="pagination-links">
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $page,
                    ));
                    ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Country</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($submissions)) : ?>
                <tr>
                    <td colspan="6">No submissions found.</td>
                </tr>
            <?php else : ?>
                <?php foreach ($submissions as $submission) : ?>
                    <tr>
                        <td><?php echo esc_html($submission['id']); ?></td>
                        <td><?php echo esc_html($submission['name']); ?></td>
                        <td><?php echo esc_html($submission['email']); ?></td>
                        <td><?php echo esc_html($submission['country']); ?></td>
                        <td><?php echo esc_html($submission['created_at']); ?></td>
                        <td>
                            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('action' => 'delete', 'id' => $submission['id'])), 'delete_submission_' . $submission['id'])); ?>" class="delete" onclick="return confirm('Are you sure you want to delete this submission?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <?php if ($total_pages > 1) : ?>
                <span class="pagination-links">
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $page,
                    ));
                    ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div> 