SobiPro.jQuery.fn.SobiProFileUploader=function(k){"use strict";var l=this;this.settings={'hideProgressBar':true,'styles':{'.progress':{'clear':'left','float':'left','margin':'10px 10px 10px 0'},'.alert':{'clear':'both'},'.file input':{'margin-bottom':'10px'},'.progress-message':{'margin-top':'10px'}}};this.settings=SobiPro.jQuery.extend(true,k,this.settings);SobiPro.jQuery.each(this.settings.styles,function(a,b){l.find(a).css(b)});var m=l.find('.bar');var n=l.find('.progress-container');var o=l.find('.progress-message');var p=l.find('.alert');var q=l.find('.idStore');var r=l.find('.upload');this.complete=function(a){l.trigger('uploadComplete',[a]);var b='100%';m.width(b);o.html(b);var c=SobiPro.jQuery.parseJSON(a.responseText);if(l.settings.hideProgressBar){n.addClass('hide')}if(c.callback){var d=window[c.callback];d(c,l)}else{p.removeClass('hide');p.addClass('alert-'+c.type);p.find('div').html(c.text);q.val(c.id);r.attr('disabled','disabled')}};this.uploadProgress=function(a,b,c,d){l.trigger('uploadProgress',[a,b,c,d]);var e=d+'%';m.width(e);o.html(e)};this.beforeSend=function(){l.trigger('beforeSend',[this]);n.removeClass('hide');var a='0%';m.width(a);o.html(a)};this.upload=function(){var e=SobiPro.jQuery.parseJSON(l.find('.upload').attr('rel'));l.trigger('createRequest',[e]);var f=l.find('.file');var g=l.find('input:file');var h=g.attr('name')+'-form';var i='<form action="'+'index.php" method="post" enctype="multipart/form-data" id="'+h+'">';for(var j in e){i+='<input type="hidden" value="'+e[j]+'" name="'+j+'"/>'}i+='</form>';i=SobiPro.jQuery(i);g.appendTo(i);var c=g.clone(g);c.appendTo(f);i.appendTo(SobiPro.jQuery('#SobiPro'));SobiPro.jQuery('#'+h).ajaxForm({'dataType':'json',beforeSend:function(){l.beforeSend()},uploadProgress:function(a,b,c,d){l.uploadProgress(a,b,c,d)},complete:function(a){l.complete(a)}}).submit()};this.find('input:file').change(function(){if(SobiPro.jQuery(this).val()){l.find('.upload, .remove').removeAttr('disabled');var a=SobiPro.jQuery(this).val();var b=(a.indexOf('\\')>=0?a.lastIndexOf('\\'):a.lastIndexOf('/'));var c=a.substring(b);if(c.indexOf('\\')===0||c.indexOf('/')===0){c=c.substring(1)}l.find('.selected').val(c);setTimeout(function(){l.upload()},500)}});this.find('.select').click(function(){l.find('input:file').trigger('click')});this.find('.remove').click(function(){var a=l.find('input:file');l.find('.upload, .remove').attr('disabled','disabled');l.find('.selected').val('');l.find('idStore').val('');a.clone(a).appendTo(a.parent());a.detach()});this.find('.upload').click(function(){l.upload()});return this};SobiPro.jQuery.fn.SPFileUploader=function(a){return this.each(function(){SobiPro.jQuery(this).SobiProFileUploader(a)})};