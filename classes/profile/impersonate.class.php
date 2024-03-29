<?php
/**
 * Impersonate quicklink
 * @author markparnell
 */
class profile_impersonate_quicklink extends bb_page_quicklink {
    private $salt_string = 'F3g*$oNGe03hsW:I*ENha9pN';

    public function __construct() {
        parent::__construct();
        $this->url = BBCONNECT_IMPERSONATE_URL.'do_impersonation.php?action=impersonate&amp;user_id='.$_GET['user_id'];
        $this->title = 'Log in as User';
        add_action('wp_footer', array($this, 'bb_impersonation'));
    }

    public function bb_impersonation() {
        if (!empty($_COOKIE['wp_bb_admin_user'])) {
            $page_url = BBCONNECT_IMPERSONATE_URL.'do_impersonation.php?action=cease_impersonation';
            $user = new WP_User(get_current_user_id());
?>
    <style>
        #bb_cease_impersonation {background-color: #999; display: inline-block; padding: 1rem; position: fixed; top: 0; left: 0; z-index: 999;}
        #bb_cease_impersonation a {color: white; cursor: pointer;}
    </style>
    <div id="bb_cease_impersonation"><a href="#" data-open="bb_impersonation_modal"><img src="<?php echo BBCONNECT_IMPERSONATE_URL.'images/activity-icon.png'; ?>" alt="Impersonation Active"></a></div>
    <div id="bb_impersonation_modal" class="reveal tiny" data-reveal>
        <p>You are currently logged in on behalf of <?php echo $user->display_name; ?> (<?php echo $user->user_email; ?>). Any actions you take will be tracked against that user.</p>
        <a href="<?php echo $page_url; ?>">Return to admin</a>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
        }
    }
}
