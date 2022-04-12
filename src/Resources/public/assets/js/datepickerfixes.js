
if (window.Class && window.Picker && window.Picker.Date) {

	Class.refactor(Picker.Date, {

		/**
		 * Do something about the American datepicker format where nothing can be done to fix it
		 * @param input
		 * @returns {*}
		 */
		getInputDate: function(input){
			if (!input || !this.options || !this.options.format) {
				return this.previous.apply(this, arguments);
			}

			let mPos = this.options.format.indexOf('%m'),
				dPos = this.options.format.indexOf('%d')
			;

			if (mPos > -1 && dPos > -1 && mPos < dPos) {
				let objDate = new Date(input.get('value'));
				if (objDate == null || !objDate.isValid()){
					return this.previous.apply(this, arguments);
				}

				// Month index starts at zero, increment or reset to 1 if 12
				let month = objDate.get('month') === 12 ? 1 : objDate.get('month')+1,
					date = objDate.get('date')
				;

				// If either of these are over 12, the normal date format should work
				if (month > 12 || date > 12) {
					return this.previous.apply(this, arguments);
				}

				// Convert to strings
				month = ''+month;
				date = ''+date;

				// Pad numbers
				month = month.length === 1 ? '0'+month : month;
				date = date.length === 1 ? '0'+date : date;

				// Clone the input, set the safely formatted value, and run the parent method
				input = input.clone();
				input.set('value', date+'-'+month+'-'+objDate.get('year'));
				arguments[0] = input;
			}

			return this.previous.apply(this, arguments);
		}
	});
}