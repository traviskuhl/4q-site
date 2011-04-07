<ul class="comments <?php echo ($comments->total==0?'no-comments':''); ?>">
	<li class="post">
		<?php
			if ( b::_('_logged') ) {
				
				// user
				$u = b::_('_u');
				
				// url
				$url = "https://github.com/{$u->login}";
			
				echo "
					<form method='post' action='/ajax/modules/comments?asset={$asset}&id={$id}'>
						<div class='avatar'>
							<a href='{$url}'><img width='40' height='40' src='https://secure.gravatar.com/avatar/{$u->gravatar_id}?s=100&d=https://d3nwyuy0nl342s.cloudfront.net%2Fimages%2Fgravatars%2Fgravatar-140.png'></a>				
						</div>
						<div class='content'>
							<textarea name='text' class='comment'></textarea>
							<button type='submit'>Post</button>
						</div>
					</form>
				";
				
			}
			else {
				echo "<div class='login'> <a href='".b::url('login', array(), array('r'=>SELF))."'>Login using GitHub</a> to ask a question </div>";
			}			
		?>
	</li>
	<?php
		foreach ( $comments as $item ) {				
				
			// url
			$url = "https://github.com/{$item->user->login}";				
			
			// print 
			echo "
				<li>
					<div class='avatar'>
						<a href='".$url."'><img width='35' height='35' src='https://secure.gravatar.com/avatar/{$item->user->gravatar_id}?s=100&d=https://d3nwyuy0nl342s.cloudfront.net%2Fimages%2Fgravatars%2Fgravatar-140.png'></a>				
					</div>
					<div class='content'>
						<a href='".$url."' class='b'>{$item->user->name}</a> ".nl2br($item->text)."
					</div>
				</li>			
			";		
		}	
	?>

</ul>
