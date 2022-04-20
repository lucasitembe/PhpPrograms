<?php
/*
  Plugin Name: Form Creator
  Plugin URI:
  Description: Form Generator wordpress plugin
  Version: 1.0
  Author: Miltone Elia Urassa
  Author URI:
  License: Eastihill

 */


global $table_prefix;

define('FORM_DATA', $table_prefix . 'data');
define('FORM_DATA_CONTENT', $table_prefix . 'data_content');
define('FORM_DATA_FIELD', $table_prefix . 'data_fields');
define('FORM_DATA_RECORD', $table_prefix . 'data_records');
define('FORM_DATA_PAGES', $table_prefix . 'data_pages');
define('FORM_DATA_CATEGORY', $table_prefix . 'data_category');
define('FORM_DATA_IMAGE', site_url().'/images');

include_once 'modules/database.php';
include_once 'lib/Form.php';
//Activation hook so the DB is created when plugin is activated
register_activation_hook(__FILE__, 'data_db_create');
$form_object = new Form();

add_action('admin_menu', 'formcreator_init');
add_action('init', 'load_form_css');
add_action('init', 'load_scripts');
add_filter('template_redirect', 'main_init');

function formcreator_init() {
    add_menu_page('Formcreator', __('Forms Management', 'formcreator'), 'manage_options', 'data', 'form_controller');
    add_submenu_page('Forms', 'Create form', 'Create Form', 'manage_options', 'field', 'field_controller');
}

function load_form_css() {
   // global $wpdb;
    //$ss= "TRUNCATE TABLE ".FORM_DATA_CONTENT;
    //$wpdb->query($ss);
    //$s= "TRUNCATE TABLE ".FORM_DATA_RECORD;
    //$wpdb->query($s);
    wp_register_style('form1-style', plugins_url('css/form-style.css', __FILE__));
    wp_enqueue_style('form1-style');
}

function load_scripts() {
    wp_enqueue_script('my_javascript_file', plugins_url('js/scripts.php', __FILE__));
    wp_enqueue_script('my_javascript_jquery', plugins_url('js/jquery.js', __FILE__));
    //wp_enqueue_script('my_javascript_magnifier', plugins_url('js/jquery.magnifier.js', __FILE__));
}

function form_controller() {
    if (isset($_GET['page']) && $_GET['page'] == 'data') {
        if (!isset($_GET['mode']) || (isset($_GET['mode']) && $_GET['mode'] == 'display')) {
            form('display');
        } else if (isset($_GET['mode']) && $_GET['mode'] == 'add') {
            form('add');
        } else if (isset($_GET['dataid']) && isset($_GET['mode']) && $_GET['mode'] == 'edit') {
            form('edit', (int) $_GET['dataid']);
        } else if (isset($_GET['dataid']) && isset($_GET['mode']) && $_GET['mode'] == 'delete') {
            form('delete', (int) $_GET['dataid']);
        }
    }
}

function form($mode, $form_id=null) {
    global $wpdb, $current_user, $form_object;

    $sql = '';
    if ($form_id == NULL) {
        $sql = "SELECT * FROM " . FORM_DATA;
    } else if ($form_id != NULL && is_int($form_id)) {
        $sql = "SELECT * FROM " . FORM_DATA . ' WHERE id=' . $form_id;
    }

    switch ($mode) {
        case "add":
            ?>
            <div class="form-content">
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <table cellspacing="0" cellpadding="0">
                        <?php
                        if (isset($_POST ['save'])) {
                            $form_object->is_empty('name', $_POST);
                            if ($form_object->is_error_occur()) {
                                $_SESSION['value_array'] = $_POST;
                                $_SESSION['error_array'] = $form_object->get_error_array();
                                $form_object->run();
                            } else {
                                $insert_data = array(
                                    'name' => trim($_POST['name']),
                                    'description' => trim($_POST['description']),
                                    'userid' => $current_user->ID,
                                    'timecreated' => time(),
                                    'timemodified' => time(),
                                );

                                if ($wpdb->insert(FORM_DATA, $insert_data)) {
                                    $update_data = array(
                                        'post_text' => '[[ form-creator : ' . trim($_POST['name']) . ' : ' . $wpdb->insert_id . ' ]]'
                                    );
                                    $wpdb->update(FORM_DATA, $update_data, array('id' => $wpdb->insert_id));
                                    redirect("admin.php?page=field&mode=display&dataid=" . $wpdb->insert_id);
                                } else {
                                    echo '<div class="fail">Fail to Save</div>';

                                    //redirect("admin.php?page=data&mode=add&success=0");
                                }
                            }
                        } else if (isset($_POST['cancel'])) {
                            redirect('admin.php?page=data');
                        }
                        ?>
                        <tr><td><label class="form-label"><?php _e('Form Name', 'formcreator'); ?></label><span class="data-required">*</span></td><td class="td-content"><input name="name" value="<?php echo $form_object->get_value('name'); ?>"/><?php _e($form_object->get_error('name'), 'formcreator'); ?></td></tr>
                        <tr><td><label class="form-label"><?php _e('Description', 'formcreator'); ?></label></td><td class="td-content"><input name="description" value="<?php echo $form_object->get_value('description'); ?>"/><?php _e($form_object->get_error('description'), 'formcreator'); ?></td></tr>
                    </table>
                    <p class="submit-area">
                        <input type="submit" value="<?php _e('Save ', 'formcreator') ?>" name="save"/>
                        <input type="reset" value="<?php _e('Clear', 'formcreator') ?>" name="reset"/>
                        <input type="submit" value="<?php _e('View Forms', 'formcreator') ?>" name="cancel"/>
                    </p>
                </form>
            </div>
            <?php
            break;


        case "display":

            $get_forms = $wpdb->get_results($sql);
            ?>
            <div class="form-content">
                <?php
                if (count($get_forms) > 0) {
                    ?>
                    <div class="create-div"><?php _e('Create ', 'formcreator'); ?><a href="admin.php?page=data&mode=add"><?php _e('new Form', 'formcreator') ?> here</a></div>
                <?php } ?>
                <table class="view" cellspacing="0" cellpadding="0">

                    <?php
                    if (count($get_forms) > 0) {
                        ?>
                        <tr>
                            <th class="first-td"><?php _e('S/No', 'formcreator'); ?></th>
                            <th><?php _e('Form Name', 'formcreator'); ?></th>
                            <th><?php _e('Description', 'formcreator'); ?></th>
                            <th><?php _e('Copy This Post in Page', 'formcreator'); ?></th>
                            <th><?php _e('Created On', 'formcreator'); ?></th>
                            <th><?php _e('Action', 'formcreator'); ?></th>
                        </tr>
                        <?php
                        $i = 1;
                        foreach ($get_forms as $key => $value) {
                            ?>
                            <tr>
                                <td class="first-td"><?php echo ($i++); ?></td>
                                <td><a href="admin.php?page=field&mode=display&dataid=<?php echo $value->id; ?>" ><?php _e($value->name, 'formcreator'); ?></a></td>
                                <td><?php _e($value->description, 'formcreator'); ?></td>
                                <td><input style="width: 250px; padding: 10px;" type="text" value="<?php _e($value->post_text); ?>"/></td>
                                <td><?php _e(date('Y-m-d', $value->timecreated)); ?></td>
                                <td>
                                    <a href="admin.php?page=data&mode=edit&dataid=<?php echo $value->id ?>"><?php _e('Edit', "formcreator"); ?></a>
                                    | <a href="admin.php?page=data&mode=delete&dataid=<?php echo $value->id ?>"><?php _e('Delete', "formcreator"); ?></a>
                                </td>
                            </tr>

                            <?php
                        }
                    } else {
                        ?>
                        <tr style="padding: 5px 0px 5px 0px;">
                            <td style="text-align: center;padding: 15px 0px 15px 0px;"><?php _e('No Forms Found.  Create ', 'formcreator') ?> <a href="admin.php?page=data&mode=add"><?php _e('new Form', 'formcreator') ?> here</a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
            break;

        case "edit":
            ?>
            <div class="form-content">
                <form method="post" style="padding: 0px; margin: 0px;" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <table cellspacing="0" cellpadding="0">
                        <?php
                        $get_forms_content = $wpdb->get_results($sql);

                        if (isset($_POST ['save'])) {
                            $form_object->is_empty('name', $_POST);
                            if ($form_object->is_error_occur()) {
                                $_SESSION['value_array'] = $_POST;
                                $_SESSION['error_array'] = $form_object->get_error_array();
                                $form_object->run();
                            } else {
                                $insert_data = array(
                                    'name' => trim($_POST['name']),
                                    'post_text' => '[[ form-creator : ' . trim($_POST['name']) . ' : ' . $get_forms_content[0]->id . ' ]]',
                                    'description' => trim($_POST['description']),
                                    'timemodified' => time()
                                );
                                if ($wpdb->update(FORM_DATA, $insert_data, array('id' => $form_id))) {
                                    echo '<div class="success">Successfull Saved</div>';
                                    //redirect('admin.php?page=data&mode=edit&dataid=' . $form_id . '&success=1');
                                } else {
                                    echo '<div class="fail">Fail to Save</div>';
                                    //redirect('admin.php?page=data&mode=edit&dataid=' . $form_id . '&success=0');
                                }
                            }
                        } else if (isset($_POST['cancel'])) {
                            redirect('admin.php?page=data');
                        }
                        ?>
                        <tr><td ><label class="form-label"><?php _e('Form Name', 'formcreator'); ?></label><span class="data-required">*</span></td><td class="td-content"><input name="name" value="<?php echo $get_forms_content[0]->name; ?>"/><?php _e($form_object->get_error('name'), 'formcreator'); ?></td></tr>
                        <tr><td><label class="form-label"><?php _e('Description', 'formcreator'); ?></label></td><td class="td-content"><input name="description" value="<?php echo $get_forms_content[0]->description; ?>"/><?php _e($form_object->get_error('description'), 'formcreator'); ?></td></tr>
                    </table>
                    <p class="submit-area">
                        <input type="submit" value="<?php _e('Save Changes ', 'formcreator') ?>" name="save"/>
                        <input type="submit" value="<?php _e('View Forms ', 'formcreator') ?>" name="cancel"/>
                    </p>
                </form>
            </div>
            <?php
            break;

        case 'delete':
            $delete_form = 'DELETE  FROM ' . FORM_DATA . ' WHERE id=' . $form_id;
            $delete_form_field = 'DELETE  FROM ' . FORM_DATA_FIELD . ' WHERE dataid=' . $form_id;
            $delete_form_records = 'DELETE  FROM ' . FORM_DATA_RECORD . ' WHERE dataid=' . $form_id;
            $get_all_record = $wpdb->get_results('SELECT * FROM ' . FORM_DATA_RECORD . ' WHERE dataid=' . $form_id);
            if (count($get_all_record) > 0) {
                foreach ($get_all_record as $key => $value) {
                    $delete_content = "DELETE  FROM " . FORM_DATA_CONTENT . ' WHERE recordid=' . $value->id;
                    $wpdb->query($delete_content);
                }
            }

            $wpdb->query($delete_form);
            $wpdb->query($delete_form_field);
            $wpdb->query($delete_form_records);

            form('display');
            //redirect('admin.php?page=data');
            break;
        default :
            break;
    }
}

function field_controller() {
    if (isset($_GET['page']) && $_GET['page'] == 'field') {

        if ((!isset($_GET['mode']) || (isset($_GET['mode']) && $_GET['mode'] == 'display')) && isset($_GET['dataid'])) {
            $dataid = $_GET['dataid'];
            fields('display', $dataid);
        } else if (isset($_GET['mode']) && $_GET['mode'] == 'add' && isset($_GET['dataid'])) {
            $dataid = $_GET['dataid'];
            fields('add', $dataid);
        } else if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_GET['dataid']) && isset($_GET['fieldid'])) {
            $form_id = $_GET['dataid'];
            $field_id = $_GET['fieldid'];
            fields('edit', $form_id, $field_id);
        } else if (isset($_GET['mode']) && $_GET['mode'] == 'delete' && isset($_GET['dataid']) && isset($_GET['fieldid'])) {
            $form_id = $_GET['dataid'];
            $field_id = $_GET['fieldid'];
            fields('delete', $form_id, $field_id);
        }
    }
}

function fields($status, $dataid, $field_id=FALSE) {
    //session_start();
    global $wpdb, $form_object, $current_user;

    switch ($status) {
        case "add":
            echo '<div class="form-content">';
            if (isset($_POST['save'])) {
                include ('fields/' . $_GET['type'] . '/' . $_GET['type'] . '.php');
                $field_object = new $_GET['type']($_POST);

                if ($field_object->insert()) {
                    redirect('admin.php?page=field&dataid=' . $field_object->data_posted['dataid']);
                } else {
                    $form_object->is_empty('name', $_POST);
                    $form_object->is_empty('param5', $_POST);
                    $_SESSION['value_array'] = $_POST;
                    $_SESSION['error_array'] = $form_object->get_error_array();
                    $form_object->run();
                }
            } else if (isset($_POST['cancel'])) {
                redirect('admin.php?page=field&dataid=' . $_POST['dataid']);
            }
            ?>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" method="post">
                <table>
                    <?php
                    include ('fields/' . $_GET['type'] . '/home.php');
                    ?>
                </table>
                <input name="dataid" value="<?php echo $_GET['dataid'] ?>" type="hidden"/>
                <input name="type" value="<?php echo $_GET['type'] ?>" type="hidden"/>
                <input name="user_id" value="<?php echo $current_user->ID; ?>" type="hidden"/>
                <p class="submit-area">
                    <input type="submit" value="<?php _e('Save ', 'formcreator') ?>" name="save"/>
                    <input type="reset" value="<?php _e('Clear', 'formcreator') ?>" name="reset"/>
                    <input type="submit" value="<?php _e('Cancel', 'formcreator') ?>" name="cancel"/>  
                </p>
            </form>
            </div>
            <?php
            break;

        case "display":
            display_field($dataid);
            break;

        case "edit":
            echo '<div class="form-content">';
            $field_object = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_FIELD . ' WHERE id=' . $field_id);
            $form_object->values = (array) $field_object[0];
            if (isset($_POST['save'])) {
                include ('fields/' . $field_object[0]->type . '/' . $field_object[0]->type . '.php');
                $field = new $field_object[0]->type($_POST);

                if ($field->update()) {
                    redirect('admin.php?page=field&dataid=' . $dataid);
                } else {
                    $form_object->is_empty('name', $_POST);
                    $form_object->is_empty('param5', $_POST);
                    $form_object->run();
                }
            } else if (isset($_POST['cancel'])) {
                redirect('admin.php?page=field&dataid=' . $dataid);
            }
            ?>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" method="post">
                <table>
                    <?php
                    include ('fields/' . $field_object[0]->type . '/home.php');
                    ?>
                </table>
                <input name="dataid" value="<?php echo $_GET['dataid'] ?>" type="hidden"/>
                <input name="type" value="<?php echo $_GET['type'] ?>" type="hidden"/>
                <input name="user_id" value="<?php echo $current_user->ID; ?>" type="hidden"/>
                <input name="fieldid" value="<?php echo $field_id; ?>" type="hidden"/>
                <p class="submit-area">
                    <input type="submit" value="<?php _e('Save ', 'formcreator') ?>" name="save"/>
                    <input type="reset" value="<?php _e('Clear', 'formcreator') ?>" name="reset"/>
                    <input type="submit" value="<?php _e('View Fields', 'formcreator') ?>" name="cancel"/>  
                </p>
            </form>
            </div>
            <?php
            break;

        case "delete":
            $field_object = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_FIELD . ' WHERE id=' . $field_id);
            include ('fields/' . $field_object[0]->type . '/' . $field_object[0]->type . '.php');
            $field = new $field_object[0]->type($_POST);
            if ($field->delete($field_id)) {
                redirect('admin.php?page=field&dataid=' . $dataid);
            } else {
                redirect('admin.php?page=field&dataid=' . $dataid);
            }
            break;
        default:
            break;
    }
}

function display_field($dataid) {
    global $wpdb;
    $get_fields = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_FIELD . ' WHERE dataid=' . $dataid);
    $get_form = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA . ' WHERE id=' . $dataid);
    ?>
    <div class="form-content">
        <div class="copy-paste"><?php _e('Copy this code in text and Paste in Your Page. Form will be displayed: ', 'formcreator');
    echo '<input style="width: 250px; padding: 10px;" type="text" value="' . $get_form[0]->post_text . '"/>'; ?></div>
        <table class="view" cellspacing="0" cellpadding="0">

            <?php
            if (count($get_fields) > 0) {
                ?>
                <tr>
                    <th class="first-td"><?php _e('S/No', 'formcreator'); ?></th>
                    <th><?php _e('Field Name', 'formcreator'); ?></th>
                    <th><?php _e('Field Type', 'formcreator'); ?></th>
                    <th><?php _e('Field description', 'formcreator'); ?></th>
                    <th><?php _e('Created On', 'formcreator'); ?></th>
                    <th><?php _e('Action', 'formcreator'); ?></th>
                </tr>
                <?php
                $i = 1;
                foreach ($get_fields as $key => $value) {
                    ?>
                    <tr>
                        <td class="first-td"><?php echo ($i++); ?></td>
                        <td><a href="admin.php?page=field&mode=edit&dataid=<?php echo $dataid; ?>&fieldid=<?php echo $value->id; ?>" ><?php _e($value->name, 'formcreator'); ?></a></td>
                        <td><?php _e(ucwords($value->type), 'formcreator'); ?></td>
                        <td><?php _e($value->description, 'formcreator'); ?></td>
                        <td><?php _e(date('Y-m-d', $value->created_on)); ?></td>
                        <td>
                            <a href="admin.php?page=field&mode=edit&dataid=<?php echo $dataid; ?>&fieldid=<?php echo $value->id; ?>"><?php _e('Edit', "formcreator"); ?></a>
                            | <a href="admin.php?page=field&mode=delete&dataid=<?php echo $dataid; ?>&fieldid=<?php echo $value->id; ?>"><?php _e('Delete', "formcreator"); ?></a>
                        </td>
                    </tr>

                    <?php
                }
            } else {
                ?>
                <tr style="padding: 5px 0px 5px 0px;">
                    <td style="text-align: center;padding: 15px 0px 15px 0px;"><?php _e('No Field Found', 'formcreator') ?> </td>
                </tr>
            <?php } ?>
        </table>
        <p class="select-field-area">
            <label style="font-size: 17px;"><?php _e('Create new Field', 'formcreator'); ?></label>
            <select name="field_name" onchange="direct_add_field(this.value,<?php echo $_GET['dataid']; ?>)">
                <option value=""><?php _e('Choose....', 'formcreator') ?></option>
                <?php
                foreach (read_fields_name() as $k => $v) {
                    echo '<option value="' . $v . '">' . __($v, 'formcreator') . '</option>';
                }
                ?>
            </select>
        </p>
    </div>
    <?php
}

function main_init() {
    add_filter('the_content', 'create_form_to_user');
 }

function create_form_to_user() {
    global $post, $form_object, $current_user, $wpdb;
    //$wpdb->query(' TRUNCATE '.FORM_DATA_CONTENT);
    //$wpdb->query(' TRUNCATE '.FORM_DATA_RECORD);
    include_once 'lib/formatting.php';
    $f = new Formatting();
    $content = $post->post_content;
    $f->format($content);

// echo '<pre>';
// print_r($f->data);
// echo '</pre>';
// exit();
    foreach ($f->data as $ss => $zz) {

        $val = trim($zz, '],[');
        $exp = explode(':', $val);
        if (count($exp) == 3) {

            $post->post_content = display_form(trim($exp[2]), $zz);
        }
    }
    echo wpautop($post->post_content,0);
}

function display_form($form_id, $searchable) {
	$search_delete=false;
    ?>
    <style type="text/css">
        .singular .entry-header, .singular .entry-content, .singular footer.entry-meta, .singular #comments-title{
            width: 95%;
        }
        .edit-link , .entry-title{
            display: none;
        }
        
        
    </style>
    <?php
    global $post, $form_object, $current_user, $wpdb;

    $return = '<form enctype="multipart/form-data"  action="' . $_SERVER["REQUEST_URI"] . '" method="post"><div class="formcreator-form">';

    $get_category = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_CATEGORY);
    $get_field_form = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_FIELD . ' WHERE dataid=' . $form_id);
    if ($_POST['Save_data_form_' . $form_id]) {
        // include_once 'lib/Form.php';
        //$validate = new Form();
        foreach ($get_field_form as $k => $v) {
            if ($v->param4 == 1) {
                $form_object->is_empty('field_' . $v->id, $_POST);
            }
//            if ($v->type == 'number' && !$form_object->numeric($_POST['field_' . $v->id]) && $_POST['field_' . $v->id] != '') {
//                $form_object->errors['field_' . $v->id] = __('Only numeric required.', 'formcreator');
//            }
            if (($v->type == 'picture' && $v->param4 == 1) || ($_POST['field_' . $v->id] != '' && $v->type == 'picture' )) {
                $form_object->valid_image('field_' . $v->id, $_FILES);
            }
        }

        $_SESSION['value_array'] = $_POST;
        $_SESSION['error_array'] = $form_object->get_error_array();
        $form_object->run();

        if ($form_object->num_errors == 0) {
            $record_id = '';
            $is_edit = FALSE;
            if (isset($_POST['record_id_value']) && trim($_POST['record_id_value']) != '') {
                $record_id = $_POST['record_id_value'];
                $is_edit = TRUE;
                $record_data = array(
                    'timemodified' => time()
                );
                $wpdb->update(FORM_DATA_RECORD, $record_data, array('id' => $record_id));
            } else {
                $record_data = array(
                    'dataid' => $_POST['dataid'],
                    'userid' => $current_user->ID,
                    'timecreated' => time(),
                    'timemodified' => time()
                );
                $wpdb->insert(FORM_DATA_RECORD, $record_data);
                $record_id = $wpdb->insert_id;
                $is_edit = FALSE;
            }
            foreach ($get_field_form as $x => $w) {

                include_once ('fields/' . $w->type . '/' . $w->type . '.php');
                $field_object_data = new $w->type(array());
                $field_object_data->insert_content($w, $record_id, $_POST['field_' . $w->id], $is_edit);
            }

            $success = TRUE;
            $form_object->values = array();
        } else {
            foreach ($form_object->errors as $err => $err_v) {
                $rr[$err_v] = $err_v;
            }
            $return.= '<div style="background-color:#EBEBEB; padding:5px 0px 5px 10px; color:blue;">';
            foreach ($rr as $erd => $errdi) {
                if ($errdi == 'required') {
                    $return.=__('All input with  ', 'formcreator') . '<span style="color:red;">*</span>' . __(' is required', 'formcreater');
                }
                if ($errdi == 'exceed_limit') {
                    $return.='<br/>' . __('You have exceeded the size limit when upload Picture', 'formcreator');
                }
            }
            $return.='</div>';
        }

        if ($success) {
            $return.= '<div style="background-color:#EBEBEB; padding:5px 0px 5px 10px; color:red;">' . __('Success saved', 'formcreator') . '</div>';
        }
    } else if (isset($_POST['search_data_' . $form_id])) {
        $search = search_data($form_id, $_POST);

        $post->post_content = str_replace($searchable, $search, $post->post_content);
    } else if (isset($_POST['view_data'])) {
        if (trim($_POST['rid']) != '') {
            $get_data_content = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_CONTENT . ' WHERE recordid=' . trim($_POST['rid']));
            foreach ($get_data_content as $content_key => $content_value) {
                $_POST['field_' . $content_value->fieldid] = $content_value->content;
            }
            $search_delete=TRUE;
            $_POST['recordid'] = $_POST['rid'];
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = array();
            $form_object->run();
        }
    }else if(isset($_POST['delete']) && $_POST['delete'] =='Delete'){
		echo 'delete<br/>';
		print_r($_POST);exit;
	}

    if (!isset($_POST['search_data_' . $form_id])) {
      //  if (isset($_POST['recordid'])) {
            $return.='<div>
                <div style="text-align:center;  border:0px; width:560px;   font-size:25px; font-weight:bold; float:left;"> Tuotekorttohjelmasi <div id="product-category"></div></div>
                <div style="float:right; padding: 10px 10px 0px  0px;" id="product-picture"> </div>
                <div style=" clear:both;"></div></div>';
      //  }
        $return.='<div class="format-data-display">';

        foreach ($get_field_form as $key => $value) {
            $field_type[] = $value->type;
            include_once ('fields/' . $value->type . '/' . $value->type . '.php');
            $field_object_data = new $value->type(array());


            $return.= $field_object_data->display_add_field($value, $form_object->get_value('field_' . $value->id));
        }
        if ($form_object->get_value('recordid')) {
            $return.='<input type="hidden" name="record_id_value" value="' . $form_object->get_value('recordid') . '"/>';
        }
        $return.='<div class="clear-float"></div></div>
            <p style="padding:0px; text-align:center;">
               <input type="hidden" name="dataid" value="' . $form_id . '"/>
                <input type="submit" name="Save_data_form_' . $form_id . '" value="' . __('Save', 'formcreator') . '"/>
                <input type="submit" value="' . __('Clear', 'formcreator') . '"/>
                <input type="submit" name="search_data_' . $form_id . '" value="' . __('Search', 'formcreator') . '"/>';
                if($search_delete==TRUE){
				$return.=' <input type="submit" name="delete" value="' . __('Delete', 'formcreator') . '"/>'	;
				}
            $return.='</p></div>
        </form>';

        $post->post_content = str_replace($searchable, $return, $post->post_content);
    }
    //echo str_replace('$value', $kk, $post->post_content);
    //print_r($post->post_content);
    //}
    return $post->post_content;
}

function search_data($form_id, $search_data=array()) {

    global $wpdb, $current_user;
    $return = '';
$return.="<script type='text/javascript' src='".plugins_url('js/jquery.magnifier.js', __FILE__)."'></script>";
    $field = array();

    if (is_array($search_data)) {

        foreach ($search_data as $key => $value) {
            $x = explode('_', $key);
            if ($x[0] == 'field') {
                if (trim($value) != '') {
                    $field[$x[1]] = $value;
                }
            }
        }

        if (count($field) == 0) {
            $return.= '<div class="set-search">' . __('Specify search criteria. Click &nbsp; ', 'formcreator') . '<a href="' . $_SERVER['REQUEST_URI'] . '">' . __('  here &nbsp; ', 'formcreator') . '</a>' . __('to go back', 'formcreator') . '</div>';
        } else {
            $get = array();
            $user_v='';
            if(array_key_exists('administrator',$current_user->wp_capabilities)){
                $user_v.= "userid !='' OR userid=''";
            }else{
                $user_v.= "userid= " .$current_user->ID;
            }
            
            $sql = " SELECT * FROM " . FORM_DATA_CONTENT . " WHERE ";

            foreach ($field as $k => $v) {
                $and = '';
                if (strstr($v, '-')) {
                    $exp = explode('-', $v);
                    $and = '  fieldid =' . $k . ' AND content >=' . (double) $exp[0] . ' AND content <=' . (int) $exp[1];
                } else {
                    $and .= '  fieldid =' . $k . ' AND content LIKE "%' . $v . '%"';
                }
                
                $l = $wpdb->get_results($sql . $and);
                if (count($l) > 0) {
                    $get[] = $l;
                }
            }
            $yy = array();
            foreach ($get as $x => $a) {
                if (is_array($a)) {
                    foreach ($a as $n => $m) {
                        $yy[$a[$n]->recordid][] = $m;
                    }
                }
            }


            $availabe_data = array();
            foreach ($yy as $xx => $zz) {
                if (count($zz) == count($field)) {
                    $availabe_data[$xx] = $zz;
                }
            }
            $return.= '<div>';
            $return.= '<table class="view_data">';
            if (count($availabe_data) == 0) {
                $return.='<tr><td>' . __('No record match your criteria. Click ', 'formcreator') . '<a href="' . $_SERVER['REQUEST_URI'] . '">' . __(' here ', 'formcreator') . '</a>' . __(' back to form', 'formcreator') . '</td></tr>';
            } else {
                $t = array();
                $remain_id_head = array();
                $return.='<tr>';
                $get_fields = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_FIELD . ' WHERE dataid=' . $form_id);

 
                if (array_key_exists(1, $get_fields)){// && $get_fields[0]->type = 'picture') {
                    $return.= '<th>' . $get_fields[1]->name . '</th>';
                    $t[] = $get_fields[1]->id;
                    
                }
                if (array_key_exists(0, $get_fields)){// && $get_fields[1]->type != 'picture') {
                    $return.= '<th >' . $get_fields[0]->name . '</th>';
                    $t[] = $get_fields[0]->id;
                  
                }
                if (array_key_exists(2, $get_fields)){// && $get_fields[2]->type != 'picture') {
                    $return.= '<th >' . $get_fields[2]->name . '</th>';
                    $t[] = $get_fields[2]->id;
                }
                if (array_key_exists(3, $get_fields) ){//&& $get_fields[3]->type != 'picture') {
                    $return.= '<th >' . $get_fields[3]->name . '</th>';
                    $t[] = $get_fields[3]->id;
                }
                if (array_key_exists(4, $get_fields)){// && $get_fields[4]->type != 'picture') {
                    $return.= '<th >' . $get_fields[4]->name . '</th>';
                    $t[] = $get_fields[4]->id;
                }
                if (array_key_exists(5, $get_fields)){// && $get_fields[4]->type != 'picture') {
                    $return.= '<th >' . $get_fields[5]->name . '</th>';
                    $t[] = $get_fields[5]->id;
                }
                //$t= swap($t, 0,1);
                
               
                  
                $return.= '<th >' . __('Action', 'formcreator') . '</th>';
                $return.='</tr>';
               
                foreach ($availabe_data as $kk => $vv) {
                    $get_record_data = $wpdb->get_results(' SELECT * FROM ' . FORM_DATA_CONTENT . ' WHERE recordid=' . $kk. ' ORDER BY fieldid');
                    
                    $get_record_data =swap($get_record_data, 0, 1);
                      $return.='<tr>';
                    foreach ($get_record_data as $data_key => $data_value) {

                        if (in_array($data_value->fieldid, $t)) {
                             if($data_value->fieldid == 2){
                                     if(trim($data_value->content) !=''){
                               $return.= '<td><img src="images/'.$data_value->content.'" class="magnify" data-magnifyby="7"  style="height:20px;width:30px; margin:0px; padding:0px;"/></td>';    
                                     }else{
                                        $return.= '<td> </td>';    
                                     }
                               
                             }else{
                              $return.= '<td>' . $data_value->content . '</td>';  
                             }
                             
                            
                        }
                    }
                    $return.='<td><form action="' . $_SERVER["REQUEST_URI"] . '" method="post"> <input type="hidden" value="' . $kk . '" name="rid"/><input type="submit" style="border:0px; text-decoration: underline; cursor: pointer; color:blue;background-color:white;" name="view_data" value="' . __('View', 'formcreator') . '"/></form></td>';
                    $return.='</tr>';
                }
            }
            $return.= '</table>';
            $return.= '</div>';
        }
    }
    return $return;
}

function swap($ary, $element1, $element2) {
    $temp = $ary[$element1];
    $ary[$element1] = $ary[$element2];
    $ary[$element2] = $temp;
    return $ary;
}

function redirect($url) {
    ?>
    <script language="javascript" type="text/javascript">
        window.location.href = '<?php echo $url; ?>';
                                                                       
    </script>
    <?php
}

function read_fields_name() {
    $dirr = ABSPATH . '/wp-content/plugins/form-creator/fields/';
    $folders = glob($dirr . '*', GLOB_ONLYDIR);
    foreach ($folders as $key => $value) {
        $d = str_replace($dirr, '', $value);
        $array[] = $d;
    }
    return $array;
}


function ac_auth_redirect() {

    if (( is_single() || is_front_page() || is_page() )
            && !is_page('login') && !is_user_logged_in()) {
         auth_redirect();
        
    }
}

add_action('template_redirect', 'ac_auth_redirect');
?>
