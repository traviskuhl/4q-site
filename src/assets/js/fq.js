YUI.add("bolt-project-fq",function(Y) {

	// shortcuts
	var $ = Y.one, $j = Y.JSON, $b = false;

	// init
	BLT.add('i',function() { BLT.Obj.cl.FQ = new BLT.Class.FQ();  } );

	// base 
	BLT.Class.FQ = function() {
		this.init();
	}

	// base prototype
	BLT.Class.FQ.prototype = {
		
		// store
		store : {},	
		
		// init
		init : function() {
		
			// set b 
			$b = BLT.Obj;	
			
			// doc
			$('#doc').on('click', this.click, this);
			
			// find posts
			if ( $('#doc .post form') ) {
				$('#doc .post form').on('submit', this.submitComment, this);
			}
			
			// scroll
			$(window).on('scroll',function(){
			
				// doc
				var doc = $(document);
				
				// are we there yet
				if ( doc.get('docScrollY') >= (doc.get('docHeight') - doc.get('winHeight')) ) {
					$("#ft").setStyle('opacity',1);
				}
				else {
					$("#ft").setAttribute("style","");
				}
			
			});
			
		},
		
		click : function(e) {
		
			// tar
			var tar = e.target,
				otar = e.target;
				
			// tags
			if ( (tar = $b.getParent(otar, 'tags')) ) {
				e.halt(); this.tags(otar);
			}
		
		},
		
		tags : function(tar) {
			
			// get the a
			var a = $b.getParent(tar, {'tag':'a'});			
			
			// tag
			var tag = a.getAttribute('data-tag');		
			
			// change it 
			$('#hd b').set('innerHTML', ' / '+a.get('innerHTML').replace(/\<em\>[0-9\(\)]+\<\/em\>/,''));
		
			// unhide all
			Y.all('ul.browse li').each(function(el){
				el.removeClass('hide');
				if ( !el.hasClass(tag) ) {		
					el.hide();
				}
				else {
					el.show();
				}
			});
		
			if ( typeof history == 'object' && typeof history.pushState == 'function' ) {
				history.pushState({'tag': tag}, '', a.getAttribute('href'));
			}			
		
		},
		
		mouse : function(event) {
		
			// targ
			var e = event;
			var tar = e.target;
			var oTar = tar;
			var self = this;
			
			// bubble
			if ( (tar = BLT.Obj.getParent(oTar,'bubble')) ) {
				$b.titleBubble(tar, e, e.type);
			}
		
		},
		
		submitComment : function(e) {
			
			// stop
			e.halt();
			
			// url
			var url = e.target.getAttribute('action');
			
			// need text
			if ( e.target.one('textarea').get('value') == '' ) {
				alert("You must enter a comment."); return;
			}
			
			// io save it 
			Y.io(url,{
				'method': 'post',
				'arguments': [e.target],
				'form': {"id":e.target},
				'on': {
					'complete': function(id, o, a) {

						// json
						var j = $j.parse(o.responseText);
											
						// logged
						if ( j.login == true ) {
							alert("You must login"); return;
						}
						
						// li
						var li = BLT.Obj.getParent(a[0], 'post');
						
						// ul
						var ul = BLT.Obj.getParent(a[0], 'comments');
					
						// inject the html
						ul.insert(j.html, li);
							
						// toggle ul				
						li.removeClass('on');
					
						// text
						a[0].one('textarea').set('value','Share your comments');						
					
					}
				}
			});	
		
		},
		
		loadUser : function(id) {

			Y.io('/ajax/pages/index/user',{
				'method': 'GET',
				'data': "id="+id,
				'on': {
					'complete': function(id, o) {
						$('.user').set('innerHTML', $j.parse(o.responseText).html);
					}
				}
			});		
		
		}
		
	}	
});