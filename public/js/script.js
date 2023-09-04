$(document)
		.one('focus.textarea', '.autoExpand', function(){
			// var savedValue = this.value;
			// this.value = '';
			this.baseScrollHeight = this.scrollHeight;
			// this.value = savedValue;
		})
		.on('input.textarea', '.autoExpand', function(){
			var minRows = this.getAttribute('data-min-rows')|0,rows;
			this.rows = minRows;
			rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 30);
			this.rows = minRows + rows;
		})