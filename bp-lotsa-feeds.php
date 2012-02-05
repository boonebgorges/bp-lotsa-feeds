<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Catches requests to our extra RSS feeds and sets up the parameters needed
 * to display the RSS feed.
 *
 * @since 1.0
 */
function bplf_catcher() {
	global $bp, $wp_query, $this_bp_feed;

	// Individual member activity feeds
	if ( bp_is_activity_component() && bp_displayed_user_id() && bp_action_variable( 0 ) && bp_action_variable( 0 ) == 'feed' ) {
		switch ( bp_current_action() ) {

			// Individual member comment feed
			// /members/username/activity/comments/feed
			case 'comments' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_COMMENTS_FEED' ) )
					$this_bp_feed = 'member_comments';
				break;

			// Individual member blog post feed
			// /members/username/activity/blogposts/feed
			case 'blogposts' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_BLOG_POSTS_FEED' ) )
					$this_bp_feed = 'member_blog_posts';
				break;

			// Individual member activity update feed
			// /members/username/activity/updates/feed
			case 'updates' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_UPDATES_FEED' ) )
					$this_bp_feed = 'member_updates';
				break;

			// Individual member friendship conection feed
			// /members/username/activity/friendships/feed
			case 'friendships' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_FRIENDSHIPS_FEED' ) )
					$this_bp_feed = 'friendships';
				break;

			// Individual member new forum topic feed
			// /members/username/activity/forumtopics/feed
			case 'forumtopics' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_TOPICS_FEED' ) )
					$this_bp_feed = 'member_topics';
				break;

			// Individual member forum reply feed
			// /members/username/activity/forumreplies/feed
			case 'forumreplies' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_REPLIES_FEED' ) )
					$this_bp_feed = 'member_replies';
				break;

			// Individual member forum activity feed
			// /members/username/activity/forums/feed
			case 'forums' :
				if ( !defined( 'BPLF_DISABLE_MEMBER_FORUMS_FEED' ) )
					$this_bp_feed = 'member_forums';
				break;
		}
	}

	// Group activity feeds
	if ( bp_is_groups_component() && isset( $bp->groups->current_group->id ) && bp_action_variable( 0 ) == 'feed' ) {

		switch ( bp_current_action() ) {

			// Group activity updates
			// /groups/groupname/updates/feed
			case 'updates' :
				if ( !defined( 'BPLF_DISABLE_GROUP_UPDATES_FEED' ) )
					$this_bp_feed = 'group_updates';
				break;

			// New group forum topics
			// /groups/groupname/forumtopics/feed
			case 'forumtopics' :
				if ( !defined( 'BPLF_DISABLE_GROUP_TOPICS_FEED' ) )
					$this_bp_feed = 'group_topics';
				break;

			// New group forum replies
			// /groups/groupname/forumreplies/feed
			case 'forumreplies' :
				if ( !defined( 'BPLF_DISABLE_GROUP_REPLIES_FEED' ) )
					$this_bp_feed = 'group_replies';
				break;

			// Group forum activity
			// /groups/groupname/forums/feed
			case 'forums' :
				if ( !defined( 'BPLF_DISABLE_GROUP_FORUMS_FEED' ) )
					$this_bp_feed = 'group_forums';
				break;

			// Group membership activity
			// /groups/groupname/membership/feed
			case 'membership' :
				if ( !defined( 'BPLF_DISABLE_GROUP_MEMBERSHIP_FEED' ) )
					$this_bp_feed = 'group_membership';
				break;

		}
	}

	// Individual forum topic feeds
	// /groups/groupname/forum/topic/topicslug/feed
	if ( bp_is_groups_component() && bp_current_action() == 'forum' && bp_action_variable( 0 ) == 'topic' && bp_action_variable( 1 ) && bp_action_variable( 2 ) == 'feed' ) {

		global $bplf_topic, $bplf_topic_posts;

		if ( !defined( 'BPLF_DISABLE_INDIVIDUAL_TOPIC_FEED' ) ) {
			$topic_id = bp_forums_get_topic_id_from_slug( bp_action_variable( 1 ) );

			$bplf_topic = bp_forums_get_topic_details( $topic_id );

			$topic_args = array(
				'per_page' => 50,
				'max'      => 50,
				'order'    => 'ASC'
			);

			$topic_args = apply_filters( 'bp_lotsa_feeds_topic_feed_args', $topic_args );

			extract( $topic_args );
			$bplf_topic_posts = new BP_Forums_Template_Topic( $topic_id, $per_page, $max, $order );

			$this_bp_feed = 'forum_topic';
		}
	}

	$this_bp_feed = apply_filters( 'bplf_which_feed', $this_bp_feed );

	if ( !$this_bp_feed )
		return false;

	$wp_query->is_404 = false;
	status_header( 200 );

	include_once( apply_filters( 'bplf_feed_template', dirname(__FILE__) . '/feed-template.php' ) );
	die;
}
add_action( 'bp_actions', 'bplf_catcher' );

/**
 * Outputs the RSS feed URL.  Used in the <link> attribute of the feed.
 *
 * @since 1.0
 */
function bplf_feed_url() {
	echo bplf_get_feed_url();
}
	/**
	 * Returns the RSS feed URL.
	 *
	 * @since 1.0
	 */
	function bplf_get_feed_url() {
		global $this_bp_feed;

		switch ( $this_bp_feed ) {
			case 'member_comments' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/comments/feed/';
				break;
			case 'member_blog_posts' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/blogposts/feed/';
				break;
			case 'member_updates' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/updates/feed/';
				break;
			case 'friendships' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/friendships/feed/';
				break;
			case 'member_topics' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/forumtopics/feed/';
				break;
			case 'member_replies' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/forumreplies/feed/';
				break;
			case 'member_forums' :
				$url = bp_displayed_user_domain() . bp_get_activity_slug() . '/forums/feed/';
				break;
			case 'group_updates' :
				$url = bp_get_groups_action_link( 'updates/feed' );
				break;
			case 'group_topics' :
				$url = bp_get_groups_action_link( 'forumtopics/feed' );
				break;
			case 'group_topics' :
				$url = bp_get_groups_action_link( 'forumreplies/feed' );
				break;
			case 'group_forums' :
				$url = bp_get_groups_action_link( 'forums/feed' );
				break;
			case 'group_membership' :
				$url = bp_get_groups_action_link( 'membership/feed' );
				break;
			case 'forum_topic' :
				$url = bp_get_groups_action_link( 'forum/topic/' . bp_action_variable( 1 ) . '/feed' );
				break;
		}

		return apply_filters( 'bplf_feed_url', $url );
	}

/**
 * Outputs the RSS feed name.  Used in the <description> attribute of the feed.
 *
 * @since 1.0
 */
function bplf_feed_name() {
	echo bplf_get_feed_name();
}
	/**
	 * Returns the RSS feed name.
	 *
	 * @since 1.0
	 */
	function bplf_get_feed_name() {
		global $this_bp_feed;

		switch ( $this_bp_feed ) {
			case 'member_comments' :
				$name = sprintf( __( '%s - Comment Feed', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'member_blog_posts' :
				$name = sprintf( __( '%s - Sitewide Blog Posts', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'member_updates' :
				$name = sprintf( __( '%s - Activity Updates', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'friendships' :
				$name = sprintf( __( '%s - Friendship Connections', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'member_topics' :
				$name = sprintf( __( '%s - New Forum Topics', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'member_replies' :
				$name = sprintf( __( '%s - Forum Replies', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'member_forums' :
				$name = sprintf( __( '%s - Forum Activity', 'bplf' ), bp_get_displayed_user_fullname() );
				break;
			case 'group_updates' :
				$name = sprintf( __( '%s - Group Updates', 'bplf' ), bp_get_current_group_name() );
				break;
			case 'group_topics' :
				$name = sprintf( __( '%s - New Group Forum Topics', 'bplf' ), bp_get_current_group_name() );
				break;
			case 'group_replies' :
				$name = sprintf( __( '%s - New Group Forum Replies', 'bplf' ), bp_get_current_group_name() );
				break;
			case 'group_forums' :
				$name = sprintf( __( '%s - Group Forum Activity', 'bplf' ), bp_get_current_group_name() );
				break;
			case 'group_membership' :
				$name = sprintf( __( '%s - Group Membership Activity', 'bplf' ), bp_get_current_group_name() );
				break;
			case 'forum_topic' :
				global $bplf_topic;
				$name = sprintf( __( '%s - Forum Topic in the Group %s', 'bplf' ), $bplf_topic->topic_title, bp_get_current_group_name() );
				break;
		}

		return apply_filters( 'bplf_feed_name', $name );
	}

/**
 * Outputs the activity loop arguments for the specified feed.
 *
 * @since 1.0
 */
function bplf_activity_args() {
	echo bplf_get_activity_args();
}
	/**
	 * Returns the activity loop arguments for the specified feed.
	 *
	 * @since 1.0
	 */
	function bplf_get_activity_args() {
		global $bp, $this_bp_feed;
		
		$max = apply_filters( 'bplf_activity_limit', 50 );

		switch ( $this_bp_feed ) {
			case 'member_comments' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=new_blog_comment';
				break;
			case 'member_blog_posts' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=new_blog_post';
				break;
			case 'member_updates' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=activity_update';
				break;
			case 'friendships' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=friendship_accepted,friendship_created';
				break;
			case 'member_topics' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=new_forum_topic';
				break;
			case 'member_replies' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=new_forum_post';
				break;
			case 'member_forums' :
				$args = 'user_id=' . bp_displayed_user_id() . '&max=' . $max . '&display_comments=stream&action=new_forum_topic,new_forum_post';
				break;
			case 'group_updates' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=' . $max . '&display_comments=stream&object=groups&action=activity_update';
				break;
			case 'group_topics' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=' . $max . '&display_comments=stream&object=groups&action=new_forum_topic';
				break;
			case 'group_replies' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=' . $max . '&display_comments=stream&object=groups&action=new_forum_post';
				break;
			case 'group_forums' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=' . $max . '&display_comments=stream&object=groups&action=new_forum_topic,new_forum_post';
				break;
			case 'group_membership' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=' . $max . '&display_comments=stream&object=groups&action=joined_group';
				break;
			case 'forum_topic' :
				global $bplf_topic, $bplf_topic_posts;

				$posts = '';
				foreach( $bplf_topic_posts->posts as $p ) {
					$posts .= $p->post_id . ',';
				}
				$posts = !empty( $posts ) ? substr( $posts, 0, -1 ) : $posts;

				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=' . $max . '&display_comments=stream&object=groups&action=new_forum_post&secondary_id=' . $posts;
				break;
		}

		return apply_filters( 'bplf_activity_args', $args );
	}

/**
 * Outputs the secondary title. Used in the second part of the <title> attribute.
 *
 * @since 1.1
 * @author r-a-y
 */
function bplf_feed_secondary_title() {
	global $this_bp_feed;

	switch ( $this_bp_feed ) {
		case 'member_comments' :
		case 'member_blog_posts' :
		case 'member_updates' :
		case 'friendships' :
		case 'member_topics' :
		case 'member_replies' :
		case 'member_forums' :
			bp_displayed_user_fullname();
			break;

		case 'group_updates' :
		case 'group_topics' :
		case 'group_replies' :
		case 'group_forums' :
		case 'group_membership' :
			echo bp_get_current_group_name();
			break;

		case 'forum_topic' :
			global $bplf_topic;

			echo $bplf_topic->topic_title;
			break;
	}

}

/**
 * Outputs the tertiary title. Used in the third part of the <title> attribute.
 *
 * @since 1.1
 * @author r-a-y
 */
function bplf_feed_tertiary_title() {
	global $this_bp_feed;

	switch ( $this_bp_feed ) {
		case 'member_comments' :
			_e( 'Blog Comments', 'bplf' );
			break;
		case 'member_blog_posts' :
			_e( 'Blog Posts', 'bplf' );
			break;
		case 'member_updates' :
			_e( 'Status Updates', 'bplf' );
			break;
		case 'friendships' :
			_e( 'Friendships', 'bplf' );
			break;
		case 'member_topics' :
			_e( 'New Forum Topics', 'bplf' );
			break;
		case 'member_replies' :
			_e( 'Forum Replies', 'bplf' );
			break;
		case 'member_forums' :
			_e( 'Forum Activity', 'bplf' );
			break;
		case 'group_updates' :
			_e( 'Group Updates', 'bplf' );
			break;
		case 'group_topics' :
			_e( 'New Topics', 'bplf' );
			break;
		case 'group_replies' :
			_e( 'Forum Replies', 'bplf' );
			break;
		case 'group_forums' :
			_e( 'Forum Activity', 'bplf' );
			break;
		case 'group_membership' :
			_e( 'Memberships', 'bplf' );
			break;
		case 'forum_topic' :
			_e( 'Topic Replies', 'bplf' );
			break;
	}

}

?>