<div itemscope itemtype="http://schema.org/WebSite">
    <meta itemprop="url" content="<?php echo home_url();?>"/>
    <form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" method="get" class="form-inline" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    	<meta itemprop="target" content="<?php echo home_url();?>?s={s}"/>      
		<input itemprop="query-input" type="text" class="form-control field" value="<?php echo get_search_query()?>" name="s" id="s" placeholder="<?php esc_attr_e( 'Tìm kiếm tin tức', 'devvn' ); ?>" />
		<button type="submit" class="btn btn-default" id="searchsubmit"><?php esc_attr_e( 'Tìm kiếm', 'devvn' ); ?></button>
    </form>
</div>