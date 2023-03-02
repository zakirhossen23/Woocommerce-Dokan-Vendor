<div class="dokan-dashboard-wrap">
   	<div class="dokan-dash-sidebar">
      	<div id="dokan-navigation" aria-label="Menu">
         	<label id="mobile-menu-icon" for="toggle-mobile-menu" aria-label="Menu">☰</label><input id="toggle-mobile-menu" type="checkbox">
         	<ul class="dokan-dashboard-menu">
            	<li class="dashboard"><a href="https://tekoa.co.ke/index.php/dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            	<li class="products"><a href="https://tekoa.co.ke/index.php/dashboard/products/"><i class="fas fa-briefcase"></i> Products</a></li>
            	<li class="orders"><a href="https://tekoa.co.ke/index.php/dashboard/orders/"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            	<li class="active quotes"><a href="https://tekoa.co.ke/index.php/dashboard/quotes/"><i class="fa fa-user"></i> Quotes/Inquiries</a></li>
            	<li class="withdraw"><a href="https://tekoa.co.ke/index.php/dashboard/withdraw/"><i class="fas fa-upload"></i> Withdraw</a></li>
            	<li class="settings"><a href="https://tekoa.co.ke/index.php/dashboard/settings/store/"><i class="fas fa-cog"></i> Settings <i class="fas fa-angle-right pull-right"></i></a></li>
            	<li class="dokan-common-links dokan-clearfix">
               	<a title="" class="tips" data-placement="top" href="https://tekoa.co.ke/store/test1/" target="_blank" data-original-title="Visit Store"><i class="fas fa-external-link-alt"></i></a>
               	<a title="" class="tips" data-placement="top" href="https://tekoa.co.ke/index.php/dashboard/edit-account/" data-original-title="Edit Account"><i class="fas fa-user"></i></a>
               	<a title="" class="tips" data-placement="top" href="https://tekoa.co.ke/wp-login.php?action=logout&amp;redirect_to=https%3A%2F%2Ftekoa.co.ke&amp;_wpnonce=46012e8d75" data-original-title="Log out"><i class="fas fa-power-off"></i></a>
            	</li>
         	</ul>
      	</div>
   	</div>
   	<div class="dokan-dashboard-content dokan-orders-content">
      	<article class="dokan-orders-area">
        	<?php
			$user = wp_get_current_user();
			if ( in_array( 'vendor', (array) $user->roles ) ) {
				echo do_shortcode('[show_buyers_quotes]');
			}
			else{
				echo do_shortcode('[show_seller_quotes]');
			}
			?>
      	</article>
   	</div>
   <!-- #primary .content-area -->
</div>