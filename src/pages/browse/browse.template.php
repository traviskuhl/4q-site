<div class="yui3-g">
	<div class="yui3-u-1-5">
		<div class="mod">
			<ul class="tags">
				<?php
					foreach ( $tags as $tag => $num ) {
						echo "<li><a data-tag='".b::makeSlug($tag)."' href='".b::url('browse',array('tag'=>$tag))."'>{$tag} <em>($num)</em></a></li>";
					}
				?>
			</ul>
			
			<ul class="tags">
			
			</ul>
		</div>
	</div>	
	<div class="yui3-u-4-5">
		
		<ul class="browse">
			<?php
				foreach ( $answers as $item ) {
					
					// tags
					$_tags = $item->tags->search->asArray();
				
					// url
					$url = b::url('profile', array('name'=>$item->by->login));
					
					// have tag and not here
					if ( $onTag AND !in_array($onTag, $_tags) ) { $_tags[] = 'hide'; }
					
					// echo
					echo "
						<li class='".implode(" ", $_tags)."'>
							<a href='$url'><img src='{$item->pic}' width='50' height='50'></a>
							<h2><a href='$url'>{$item->by->name}</a></h2>
							<cite>{$item->job} - {$item->loc}</cite>
							<div>added ".$item->ago('added')."</div>
						</li>
					";
				}
			?>			
		</ul>
			
	</div>
</div>