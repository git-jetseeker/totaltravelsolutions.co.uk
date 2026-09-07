(function($) {
	
	"use strict";

	var date1			=$('.checkin_date'),
		date2			=$('.dpd2');
	
	
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	 
	var checkin = date1.datepicker({		
	    format: 'dd/mm/yyyy',
		onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		var newDate = new Date(ev.date)
		newDate.setDate(newDate.getDate() + 7);
		checkout.setValue(newDate);
		checkin.hide();
		
	}).data('datepicker');
	
	var checkout = date2.datepicker({		
	    format: 'dd/mm/yyyy',
		onRender: function(date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
		
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');
	

})(jQuery);
