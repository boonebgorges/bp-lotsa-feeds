<?php

function bplf_catcher() {
	global $bp, $wp_query, $this_bp_feed;

	// Individual member activity feeds
	if ( $bp->current_component == $bp->activity->slug && $bp->displayed_user->id && $bp->action_variables[0] == 'feed' ) {
		switch ( $bp->current_action ) {
			
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
	if ( $bp->current_component == $bp->groups->slug && $bp->groups->current_group->id && $bp->action_variables[0] == 'feed' ) {
		switch ( $bp->current_action ) {
		
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
	if ( $bp->current_component == $bp->groups->slug && $bp->current_action == 'forum' && $bp->action_variables[0] == 'topic' && $bp->action_variables[1] && $bp->action_variables[2] == 'feed' ) {
		global $bplf_topic, $bplf_topic_posts;
		
		if ( !defined( 'BPLF_DISABLE_INDIVIDUAL_TOPIC_FEED' ) ) {
			$topic_id = bp_forums_get_topic_id_from_slug( $bp->action_variables[1] );
			$bplf_topic = bp_forums_get_topic_details( $topic_id );	
			$bplf_topic_posts = new BP_Forums_Template_Topic( $topic_id );
			
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
add_action( 'wp', 'bplf_catcher', 3 );


function bplf_feed_url() {
	echo bplf_get_feed_url();
}
	function bplf_get_feed_url() {
		global $bp, $this_bp_feed;
		
		switch ( $this_bp_feed ) {
			case 'member_comments' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/comments/feed';
				break;
			case 'member_blog_posts' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/blogposts/feed';
				break;
			case 'member_updates' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/updates/feed';
				break;
			case 'friendships' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/friendships/feed';
				break;
			case 'member_topics' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/forumtopics/feed';
				break;
			case 'member_replies' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/forumreplies/feed';
				break;
			case 'member_forums' :
				$url = $bp->displayed_user->domain . $bp->activity->slug . '/forums/feed';
				break;
			case 'group_updates' :
				$url = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/updates/feed';
				break;
			case 'group_topics' :
				$url = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/forumtopics/feed';
				break;
			case 'group_topics' :
				$url = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/forumreplies/feed';
				break;
			case 'group_forums' :
				$url = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/forums/feed';
				break;
			case 'group_membership' :
				$url = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/membership/feed';
				break;
			case 'forum_topic' :
				$url = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/forum/topic/' . $bp->action_variables[1] . '/feed';
				break;
		}
	
		return apply_filters( 'bplf_feed_url', $url );
	}


function bplf_feed_name() {
	echo bplf_get_feed_name();
}
	function bplf_get_feed_name() {
		global $bp, $this_bp_feed;
		
		switch ( $this_bp_feed ) {
			case 'member_comments' :
				$name = sprintf( __( '%s - Comment Feed', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'member_blog_posts' :
				$name = sprintf( __( '%s - Sitewide Blog Posts', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'member_updates' :
				$name = sprintf( __( '%s - Activity Updates', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'friendships' :
				$name = sprintf( __( '%s - Friendship Connections', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'member_topics' :
				$name = sprintf( __( '%s - New Forum Topics', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'member_replies' :
				$name = sprintf( __( '%s - Forum Replies', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'member_forums' :
				$name = sprintf( __( '%s - Forum Activity', 'bplf' ), $bp->displayed_user->fullname );	
				break;
			case 'group_updates' :
				$name = sprintf( __( '%s - Group Updates', 'bplf' ), $bp->groups->current_group->name );	
				break;
			case 'group_topics' :
				$name = sprintf( __( '%s - New Group Forum Topics', 'bplf' ), $bp->groups->current_group->name );	
				break;
			case 'group_replies' :
				$name = sprintf( __( '%s - New Group Forum Replies', 'bplf' ), $bp->groups->current_group->name );	
				break;
			case 'group_forums' :
				$name = sprintf( __( '%s - Group Forum Activity', 'bplf' ), $bp->groups->current_group->name );	
				break;
			case 'group_membership' :
				$name = sprintf( __( '%s - Group Membership Activity', 'bplf' ), $bp->groups->current_group->name );	
				break;
			case 'forum_topic' :
				global $bplf_topic;
				$name = sprintf( __( '%s - Forum Topic in the Group %s', 'bplf' ), $bplf_topic->topic_title, $bp->groups->current_group->name );	
				break;
		}
		
		return apply_filters( 'bplf_feed_name', $name );	
	}



function bplf_activity_args() {
	echo bplf_get_activity_args();
}
	function bplf_get_activity_args() {
		global $bp, $this_bp_feed;
		
		switch ( $this_bp_feed ) {
			case 'member_comments' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=new_blog_comment';
				break;
			case 'member_blog_posts' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=new_blog_post';
				break;			
			case 'member_updates' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=activity_update';
				break;
			case 'friendships' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=friendship_accepted,friendship_created';
				break;
			case 'member_topics' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=new_forum_topic';
				break;
			case 'member_replies' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=new_forum_post';
				break;
			case 'member_forums' :
				$args = 'user_id=' . $bp->displayed_user->id . '&max=50&display_comments=stream&action=new_forum_topic,new_forum_post';
				break;
			case 'group_updates' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=50&display_comments=stream&object=groups&action=activity_update';
				break;
			case 'group_topics' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=50&display_comments=stream&object=groups&action=new_forum_topic';
				break;
			case 'group_replies' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=50&display_comments=stream&object=groups&action=new_forum_post';
				break;
			case 'group_forums' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=50&display_comments=stream&object=groups&action=new_forum_topic,new_forum_post';
				break;
			case 'group_membership' :
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=50&display_comments=stream&object=groups&action=joined_group';
				break;
			case 'forum_topic' :
				global $bplf_topic, $bplf_topic_posts;
				
				$posts = '';
				foreach( $bplf_topic_posts->posts as $p ) {
					$posts .= $p->post_id . ',';
				}
				
				$args = 'primary_id=' . $bp->groups->current_group->id . '&max=50&display_comments=stream&object=groups&action=new_forum_topic,new_forum_post&secondary_id=' . $bplf_topic->topic_id . ',' . $posts;
				break;
		}
		
		return apply_filters( 'bplf_activity_args', $args );	
	}

?>