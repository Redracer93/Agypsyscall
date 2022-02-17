<?php
// define( 'CBAFFMACH_CB_API_URL', 'http://localhost/cbmachine/api.php' );
define( 'CBAFFMACH_CB_API_URL', 'https://cbsense.com/app/api.php' );
define( 'CBAFFMACH_CB_API_KEY', 'cbmachine353' );
define( 'CBAFFMACH_CB_PER_PAGE', 20 );
/* Product Search */

function cbaffmach_settings_research() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
    wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-tag-machine' ) );
    $category = isset( $_GET['cat'] ) ? intval( $_GET['cat'] ) : 0;
    $page = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 0;
    ?>
    <div class="wrap">
    <?php cbaffmach_header();?>
    <h1>Research</h1>
    <br/>
    <?php cbaffmach_cb_filters(); ?>
    <?php
        if( cbaffmach_cb_filter_applied() ) {
            $products = cbaffmach_search_products( $page );
        }
        else {
            cbaffmach_cb_all_cats();
            $products = cbaffmach_cb_top_products( $category, $page );
        }
    ?>
    </div>
    <div id="cblink-modal" class="ammodal" style="display:none">
        <h3>Your Affiliate Link: </h3>
        <div style="">
        <span><b>Your affiliate id (optional)</b></span>
        <input type="text" val="" id="cb-your-tracking-id" style="width:82%"><br/><br/>
        <span><b>Your affiliate link</b></span>
        <input type="text" val="" id="cb-your-aff-link" style="width:82%;" /><button class="button button-secondary cbaffmach-copy" data-clipboard-target="#cb-your-aff-link"><i class="fas fa-copy"></i> Copy</button>
        </div>
        <br/>
        <a  class="button button-primary" href="" rel="ammodal:close"><i class="fas fa-times"></i> Close</a>
    </div>
    <?php
}

/* Visual */

function cbaffmach_cb_top_products( $category = 0, $page = 0, $per_page = CBAFFMACH_CB_PER_PAGE ) {
    $args = array();
    // delete_transient( 'cbaffmach_top_prods' );
    if( empty( $page ) && empty( $category ) ) {
        // Default View, transient
        if ( isset( $_GET[ 'reset_transient'] ) || ( false === ( $products = get_transient( 'cbaffmach_top_prods' ) ) ) ) {
            $args = cbaffmach_cb_pagination_args( $args, $page, $per_page );
            $products = cbaffmach_cb_request( 'top_products', $args );
            set_transient( 'cbaffmach_top_prods', $products, 3 * HOUR_IN_SECONDS );
        }
    }
    else {
        if( $category )
            $args['cat'] = $category;
        $args = cbaffmach_cb_pagination_args( $args, $page, $per_page );
        $products = cbaffmach_cb_request( 'top_products', $args );
    }
    // var_dump($products);

    cbaffmach_cb_products_table( $products );
}

function cbaffmach_cb_all_cats() {
    if ( false === ( $categories = get_transient( 'cbaffmach_cats' ) ) ) {
        $categories = cbaffmach_cb_get_all_cats();
        set_transient( 'cbaffmach_cats', $categories, 1 * HOUR_IN_SECONDS );
    }
    cbaffmach_cb_categories_list( $categories );
}

function cbaffmach_cb_get_all_cats() {
    return cbaffmach_cb_request( 'categories' );
}

function cbaffmach_cb_products_table( $products ) {
    $category_id = isset( $_GET['cat'] ) ? intval( $_GET['cat'] ) : 0;
    $current_page = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 1;

    // var_dump($products);
    if( empty( $products ) || empty( $products['results'] ) || is_null( $products['results']  ) ) {
        echo '<p>No results</p>';
        return false;
    }
    // var_dump( $products );
    $total_prods = intval( $products['total'] );
    $str = '<span class="cbnres">Total Products: '.$total_prods.'</span>';
    $str .= '<table class="widefat table-prods"><thead>';
    $str .= '<tr><th scope="col">#</th><th scope="col">Gravity</th><th scope="col">Name</th><th scope="col">Category</th><th scope="col">EPS</th><th scope="col">% Comm</th><th scope="col">Recurring</th><th scope="col">Description</th><th style="width:120px;"></th></tr></thead><tbody>';
    $i = ( ( $current_page - 1 ) * CBAFFMACH_CB_PER_PAGE ) + 1;
    foreach( $products['results'] as $product ) {
        $str.= cbaffmach_cb_row( $product, $i++ );
    }
    $str .= '</tbody></table>';

    if( $products['total'] > CBAFFMACH_CB_PER_PAGE ) {
        // Paginación
        $num_of_pages = ceil( $products['total'] / CBAFFMACH_CB_PER_PAGE );
        // var_dump($num_of_pages);
        $page_links = paginate_links( array(
            'base' => add_query_arg( array(
                    'pagenum' => '%#%',
                    'cat' => $category_id
                )
            ),
            'format' => '',
            'end_size' => 4,
            'mid_size' => 3,
            'prev_text' => __( '«', 'text-domain' ),
            'next_text' => __( '»', 'text-domain' ),
            'total' => $num_of_pages,
            'current' => $current_page
        ) );
        if ( $page_links ) {
            $str .= '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0;">' . $page_links . '</div></div>';
        }
    }
    echo $str;
}

function cbaffmach_cb_row( $product, $pos = 0 ) {
    // $
    // var_dump($product);
    $created = $product[16].' 00:00:00';
    $clickbank_id = cbaffmach_get_user_cb_id();
    $created = cbaffmach_since( $created, 1 )/*.' '.$product[16]*/;
    $aff_info = '<span><a href="http://'.$clickbank_id.'.'.$product[1].'.hop.clickbank.net/" class="button button-secondary" target="_blank"><i class="fas fa-external-link-alt" title="View Sales Page"></i></a>';
    if( isset( $product[17] ) && !empty( $product[17] ) )
        $aff_info .= ' <a href="'.$product[17].'" class="button button-secondary" target="_blank" title="View Promotion Info"><i class="fas fa-search-dollar"></i></a>';
    $aff_info .= ' <a href="#" class="button button-secondary btn-aff-link" data-product-id="'.$product[1].'" title="Get Affiliate Link"><i class="fas fa-link"></i></a>';
    $aff_info .= '</span>';
    $str = '<tr>
        <th scope="row">'.$pos.'</th>
        <td>'.cbaffmach_gravity_color( round( $product[6] ) ).'</td>
        <td class="nocent"><b>'.$product[2].'</b></td>
        <td>'.$product[5].'</td>
        <td>$'.round( $product[11], 2).'</td>
        <td>'.$product[15].'</td>
        <td>'.( $product[8] ? '<span title="'.$product[15].'%, $'.round( $product[13], 2 ).' Recurring EPC"><i class="fas fa-sync"></i></span>' : '').'</td>
        <td class="nocent">'.cbaffmach_linkify( str_replace( 'Http', 'http', $product[3] ) ).'</td>
        <!--<td>'.$created.'</td>-->
        <td>'.$aff_info.'</td>
    </tr>';
    return $str;
}

function cbaffmach_gravity_color( $val ) {
    $class = '';
    if( $val >= 100 )
        $class = 'green';
    else if( $val >= 10 )
        $class = 'orange';
    else
        $class = 'red';
    return '<span style="color:'.$class.' ">'.$val.'</span>';
}
function cbaffmach_cb_categories_list( $categories ) {
    if( empty( $categories ) )
        return;
    $str = '<p class="cb-cat-list"><b> CATEGORIES: </b>';
    $i = 0;
    $total_prods = cbaffmach_total_prods_cat( $categories );
    $str .= '<a href="'.admin_url( 'admin.php?page=cb-automator-research&cat=0' ).'">All ('.$total_prods.')</a>, ';
    foreach( $categories as $category ) {
        if( $i++ )
            $str .= ', ';
        $str .= '<a href="'.admin_url( 'admin.php?page=cb-automator-research&cat='.$category[0] ).'">'.$category[1].' ('.$category[2].')</a>';
    }
    $str .= '</p>';
    echo $str;
}

function cbaffmach_total_prods_cat( $categories ) {
    $total = 0;
    if( !empty( $categories ) ) {
        foreach( $categories as $category ) {
            $total += intval( $category[2] );
        }
    }
    return $total;
}

function cbaffmach_cb_filters() {
    $name = isset( $_GET['cb_name'] ) ? trim( $_GET['cb_name'] ) : '';
    $category = isset( $_GET['cat'] ) ? intval( $_GET['cat'] ) : 0;
    $min_gravity = isset( $_GET['cb_gravity'] ) ? intval( $_GET['cb_gravity'] ) : 0;
    $recurring = isset( $_GET['recurring'] ) ? intval( $_GET['recurring'] ) : 0;
    $min_comm = isset( $_GET['cb_comm'] ) ? intval( $_GET['cb_comm'] ) : 0;
    $age = isset( $_GET['cb_age'] ) ? intval( $_GET['cb_age'] ) : 0;
    $eps = isset( $_GET['cb_eps'] ) ? intval( $_GET['cb_eps'] ) : 0;
    $yesno = cbaffmach_yes_no();
    $cats = cbaffmach_cb_categories();
    $commissions = cbaffmach_min_comm();
    $ages = cbaffmach_prod_ages();
?>
    <form method="GET">
    <input type="hidden" name="page" value="cb-automator-research">
    <div class="cb-filters">
        <h3>Filter Offers</h3>
    <div class="cb-filter"><label for="cb_name">Name: </label><input type="text" name="cb_name" id="cb_name" value="<?php echo $name;?>" style="width: 200px" /></div>
    <div class="cb-filter"><label for="cb_cat">Category: </label><select name="cat" id="cb_cat"><?php cbaffmach_select_options( $category, $cats, 'Any' );?></select></div>
    <div class="cb-filter"><label for="cb_gravity">Min Gravity: </label><input type="number" name="cb_gravity" id="cb_gravity" value="<?php echo $min_gravity;?>" style="width:65px" /></div>
    <div class="cb-filter"><label for="cb_eps">Min EPS ($): </label><input type="number" name="cb_eps" id="cb_eps" value="<?php echo $eps;?>"  style="width:65px" /></div>
    <div class="cb-filter"><label for="recurring">Recurring Offer: </label><select name="recurring" id="recurring"><?php cbaffmach_select_options( $recurring, $yesno );?></select></div>
    <div class="cb-filter"><label for="cb_comm">Minimum Commission (%): </label><select name="cb_comm" id="cb_comm"><?php cbaffmach_select_options( $min_comm, $commissions );?></select></div>
    <!-- <div class="cb-filter"><label for="cb_age">Age: </label><select name="cb_age" id="cb_age"><?php cbaffmach_select_options( $age, $ages );?></select></div><br/> -->
    <button type="submit" class="button button-primary"><i class="fas fa-search"></i> Search Products</button> <a href="<?php echo admin_url( 'admin.php?page=cb-automator-research' );?>" class="button button-secondary"><i class="fas fa-list"></i> View All</a>
    </div>

    </form>
<?php
}

function cbaffmach_search_products( $page = 0, $per_page = CBAFFMACH_CB_PER_PAGE ) {
    $category = isset( $_GET['cat'] ) ? intval( $_GET['cat'] ) : 0;
    $name = isset( $_GET['cb_name'] ) ? trim( $_GET['cb_name'] ) : '';
    $gravity = isset( $_GET['cb_gravity'] ) ? intval( $_GET['cb_gravity'] ) : 0;
    $eps = isset( $_GET['cb_eps'] ) ? intval( $_GET['cb_eps'] ) : 0;
    $recurring = isset( $_GET['recurring'] ) ? intval( $_GET['recurring'] ) : 0;
    $min_comm = isset( $_GET['cb_comm'] ) ? intval( $_GET['cb_comm'] ) : 0;
    $age = isset( $_GET['cb_age'] ) ? intval( $_GET['cb_age'] ) : 0;
    $args = array();
    if( $category )
        $args['cat'] = $category;
    if( !empty( $name ) )
        $args['name'] = $name;
    if( !empty( $gravity ) )
        $args['min_gravity'] = $gravity;
    if( !empty( $eps ) )
        $args['min_eps'] = $eps;
    if( !empty( $recurring ) )
        $args['recurring'] = $recurring;
    if( !empty( $min_comm ) )
        $args['min_comm'] = $min_comm;
    if( !empty( $age ) )
        $args['age'] = $age;
    $args = cbaffmach_cb_pagination_args( $args, $page, $per_page );
    $products = cbaffmach_cb_request( 'search', $args );
    // var_dump($products);

    cbaffmach_cb_products_table( $products );
}

function cbaffmach_cb_filter_applied() {
    if( isset( $_GET['cb_gravity'] ) || isset( $_GET['cb_name'] ) )
        return true;
    return false;
}
/* Database */

function cbaffmach_cb_categories() {
    $cats = array(
        array( 'value' => 1, 'label' => 'Arts & Entertainment'),
        array( 'value' => 2, 'label' => 'As seen on TV'),
        array( 'value' => 3, 'label' => 'Betting Systems'),
        array( 'value' => 4, 'label' => 'Business / Investing'),
        array( 'value' => 5, 'label' => 'Computers / Internet'),
        array( 'value' => 6, 'label' => 'Cooking, Food & Wine'),
        array( 'value' => 7, 'label' => 'E-business & E-marketing'),
        array( 'value' => 8, 'label' => 'Education'),
        array( 'value' => 9, 'label' => 'Employment & Jobs'),
        array( 'value' => 10, 'label' => 'Fiction'),
        array( 'value' => 11, 'label' => 'Games'),
        array( 'value' => 12, 'label' => 'Green Products'),
        array( 'value' => 13, 'label' => 'Health & Fitness'),
        array( 'value' => 14, 'label' => 'Home & Garden'),
        array( 'value' => 15, 'label' => 'Languages'),
        array( 'value' => 16, 'label' => 'Mobile'),
        array( 'value' => 17, 'label' => 'Parenting & Families'),
        array( 'value' => 18, 'label' => 'Politics / Current Events'),
        array( 'value' => 19, 'label' => 'Reference'),
        array( 'value' => 20, 'label' => 'Self-Help'),
        array( 'value' => 21, 'label' => 'Software & Services'),
        array( 'value' => 22, 'label' => 'Spirituality, New Age & Alternative Beliefs'),
        array( 'value' => 23, 'label' => 'Sports'),
        array( 'value' => 24, 'label' => 'Travel')
    );
    return $cats;
}

function cbaffmach_yes_no( ) {
    return array(
        array( 'value' => 0, 'label' => 'Any' ),
        array( 'value' => 1, 'label' => 'Yes' ),
        array( 'value' => 2, 'label' => 'No' )
    );
}

function cbaffmach_min_comm( ) {
    return array(
        array( 'value' => 0, 'label' => 'Any' ),
        array( 'value' => 50, 'label' => '50% +' ),
        array( 'value' => 60, 'label' => '60% +' ),
        array( 'value' => 75, 'label' => '75% +' )
    );
}

function cbaffmach_prod_ages( ) {
    return array(
        array( 'value' => 0, 'label' => 'Any' ),
        array( 'value' => 1, 'label' => 'New (less than 3 months)' ),
        array( 'value' => 2, 'label' => 'Established (1 year or more)' ),
        array( 'value' => 3, 'label' => 'Old (3 years or more)' ),
    );
}

function cbaffmach_cb_pagination_args( $args, $page = 0, $per_page = CBAFFMACH_CB_PER_PAGE ) {
    if( $page )
        $page--;
    $args[ 'page' ] = $page;
    $args[ 'per_page' ] = $per_page;
    return $args;
}
/* API functions */

function cbaffmach_cb_request( $action = 'search', $args = array() ) {
    $url = CBAFFMACH_CB_API_URL.'?key='.CBAFFMACH_CB_API_KEY.'&action='.$action;
    if( !empty( $args ) ) {
        foreach( $args as $key => $arg )
            $url .= '&'.$key.'='.$arg;
    }
    // echo $url;
// echo $url;
    $res = wp_remote_get( $url );
    if ( is_wp_error( $res ) )
        return false;
    $data = wp_remote_retrieve_body( $res );

    if ( is_wp_error( $data ) )
        return false;
    $data = json_decode( $data );
    // var_dump($data);
    if( empty( $data ) )
        return false;
    if( $data->result == 0 )
        return false;
    if( isset( $data->text ) ) {
        return json_decode( $data->text, true );
    }
    return false;
}

function cbaffmach_get_cb_link_ajax() {
    $product_id = trim( $_POST['product_id'] );
    if( !$product_id )
        exit();
    $clickbank_id = cbaffmach_get_user_cb_id();
    if( !empty( $clickbank_id ) )
        echo 'http://'.$clickbank_id.'.'.$product_id.'.hop.clickbank.net';
    else
        echo 'Please enter your Clickbank Id in settings first';
    exit();
}

function cbaffmach_get_user_cb_id() {
    // return 'xxx';
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['import'] ) ? $settings['import'] : false;
    $clickbankid = isset( $settings['clickbankid'] ) ? trim( $settings['clickbankid'] ) : '';
    if( empty( $clickbankid ) )
        return 'forexrt';
    return $clickbankid;
}
?>