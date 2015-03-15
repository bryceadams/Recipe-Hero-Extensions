jQuery(document).ready(function($){

	$('.recipe-hero-likes').live('click',
	    function() {
    		var link = $(this);
    		if(link.hasClass('active')) return false;
		
    		var id = $(this).attr('id'),
    			postfix = link.find('.recipe-hero-likes-postfix').text();
			
    		$.post(recipe_hero_likes.ajaxurl, { action:'recipe-hero-likes', likes_id:id, postfix:postfix }, function(data){
    			link.html(data).addClass('active').attr('title','You already like this');
    		});
		
    		return false;
	});
	
	if( $('body.ajax-recipe-hero-likes').length ) {
        $('.recipe-hero-likes').each(function(){
    		var id = $(this).attr('id');
    		$(this).load(recipe_hero_likes.ajaxurl, { action:'recipe-hero-likes', post_id:id });
    	});
	}

});