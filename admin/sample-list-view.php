<div class="wrap">
    <h1 class="wp-heading-inline">Pages</h1>
    <a href="https://trifectacbd.com/wp-admin/post-new.php?post_type=page" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

<?php echo wi_admin_message('message by me') ?>
    
    <ul class="subsubsub">
        <li class="all"><a href="edit.php?post_type=page" class="current" aria-current="page">All <span class="count">(36)</span></a> |</li>
        <li class="publish"><a href="edit.php?post_status=publish&amp;post_type=page">Published <span class="count">(32)</span></a> |</li>
        <li class="draft"><a href="edit.php?post_status=draft&amp;post_type=page">Drafts <span class="count">(4)</span></a> |</li>
        <li class="trash"><a href="edit.php?post_status=trash&amp;post_type=page">Trash <span class="count">(3)</span></a></li>
    </ul>
    <form id="posts-filter" method="get">

        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Search Pages:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Pages">
        </p>

        <input type="hidden" name="post_status" class="post_status_page" value="all">
        <input type="hidden" name="post_type" class="post_type_page" value="page">

        <input type="hidden" id="_wpnonce" name="_wpnonce" value="5a794bc44c">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/edit.php?post_type=page">
        <div class="tablenav top">

            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1">Bulk Actions</option>
                    <option value="edit" class="hide-if-no-js">Edit</option>
                    <option value="trash">Move to Trash</option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Apply">
            </div>

            <div class="alignleft actions">
                <label for="filter-by-date" class="screen-reader-text">Filter by date</label>
                <select name="m" id="filter-by-date">
                    <option selected="selected" value="0">All dates</option>
                    <option value="201911">November 2019</option>
                    <option value="201910">October 2019</option>
                    <option value="201909">September 2019</option>
                </select>
                <input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter"> 
            </div>
            
            <div class="tablenav-pages"><span class="displaying-num">36 items</span>
                <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current Page</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> of <span class="total-pages">2</span></span>
                </span>
                <a class="next-page button" href="https://trifectacbd.com/wp-admin/edit.php?post_type=page&amp;paged=2"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
            </div>
            <br class="clear">
        </div>
        
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                        <input id="cb-select-all-1" type="checkbox">
                    </td>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="https://trifectacbd.com/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc">
                            <span>Title</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="author" class="manage-column column-author">Author</th>
                    <th scope="col" id="comments" class="manage-column column-comments num sortable desc">
                        <a href="https://trifectacbd.com/wp-admin/edit.php?post_type=page&amp;orderby=comment_count&amp;order=asc">
                            <span>
                                <span class="vers comment-grey-bubble" title="Comments">
                                <span class="screen-reader-text">Comments</span>
                            </span>
                        </span>
                        <span class="sorting-indicator"></span></a>
                    </th>
                    <th scope="col" id="date" class="manage-column column-date sortable asc">
                        <a href="https://trifectacbd.com/wp-admin/edit.php?post_type=page&amp;orderby=date&amp;order=desc">
                            <span>Date</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>

            <tbody id="the-list">

                <?php 
                
                for($i=1; $i<=10;$i++):
                ?>
                <tr id="post-1149" class="iedit author-self level-0 post-1149 type-page status-publish hentry simple-restrict-permission-wholesale">
                    <th scope="row" class="check-column">
                        <input id="cb-select-1149" type="checkbox" name="post[]" value="1149"/>
                    </th>
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        
                        <strong>
                            <a class="row-title" href="https://trifectacbd.com/wp-admin/post.php?post=1149&amp;action=edit" aria-label="“250MG Full Spectrum CBD Oil” (Edit)">250MG Full Spectrum CBD Oil</a>
                        </strong>

                        
                        <div class="row-actions">
                            <span class="edit">
                                <a href="https://trifectacbd.com/wp-admin/post.php?post=1149&amp;action=edit" aria-label="Edit “250MG Full Spectrum CBD Oil”">Edit</a> | 
                            </span>
                            <span class="inline hide-if-no-js">
                                <button type="button" class="button-link editinline" aria-label="Quick edit “250MG Full Spectrum CBD Oil” inline" aria-expanded="false">Quick&nbsp;Edit</button> | 
                            </span>
                            <span class="trash">
                                <a href="https://trifectacbd.com/wp-admin/post.php?post=1149&amp;action=trash&amp;_wpnonce=1728e93ed8" class="submitdelete" aria-label="Move “250MG Full Spectrum CBD Oil” to the Trash">Trash</a> | 
                            </span>
                            <span class="view"><a href="https://trifectacbd.com/250mg-full-spectrum-cbd-oil/" rel="bookmark" aria-label="View “250MG Full Spectrum CBD Oil”">View</a> | </span>
                            <span class="tcb">
                                <span class="thrive-adminbar-icon"></span>
                                <a target="_blank" href="https://trifectacbd.com/wp-admin/post.php?post=1149&amp;action=architect&amp;tve=true">Edit with Thrive Architect</a>
                            </span>
                        </div>
                    </td>
                    <td class="author column-author" data-colname="Author">
                        <a href="edit.php?post_type=page&amp;author=1">James Ballas</a>
                    </td>
                    <td class="comments column-comments" data-colname="Comments">
                        <div class="post-com-count-wrapper">
                            <span aria-hidden="true">—</span><span class="screen-reader-text">No comments</span><span class="post-com-count post-com-count-pending post-com-count-no-pending"><span class="comment-count comment-count-no-pending" aria-hidden="true">0</span><span class="screen-reader-text">No comments</span></span>
                        </div>
                    </td>
                    <td class="date column-date" data-colname="Date">Published
                        <br><abbr title="2019/10/30 5:08:51 pm">2019/10/30</abbr></td>
                </tr>

                <?php endfor; ?>
                
            </tbody>
            
        </table>
        
    </form>

 

    <div id="ajax-response"></div>
    <br class="clear">
</div>