/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
    finance.init();
});
finance = function () {
    return {
        init: function () {
            finance.addEvents(jQuery('body'));
        }, addEvents: function (html) {

            html.find('.search-user').click(function () {
                finance.fetchUser(jQuery(this));
                return false;
            });

            html.find('form').submit(function () {
                return finance.validate();
            });

            return html;
        }, validate: function () {

            var title = 'Validation Error';
            var description = '';

            var user_id = parseInt(jQuery('#user_id').val());
            var amount = parseInt(jQuery('#amount').val());

            if (!user_id && jQuery('#user_id').length) {
                description += ':- User Not Selected.<br>';
            }

            if (!amount) {
                description += ':- Amount can not be less that 1.<br>';
            }

            if (description != '') {
                kazist.showDialog(title, description, 'error');
                return false;
            }

            return true;


        }, fetchUser: function (this_element) {

            var search_wrapper = this_element.closest('.search-user-wrapper');
            var keyword = search_wrapper.find('.search_user_id').val();

            if (keyword == '' || keyword == ' ') {
                finance.printMessage('<b style="color:red">User to Search is Empty:</b>');
                return false;
            }

            var data_object = {keyword: keyword};
            var form = kazist.callAjaxByRoute('users.users.fetchuser', data_object, true);
            if (form == null || typeof form == 'undefined') {

                finance.printMessage('<b style="color:red">User Not Found:</b> <i>"' + keyword + '"</i><br>');
            } else {
                var html = '';
                html += '<b>User Name:</b> ' + form.username + '<br>';
                html += '<b>Email:</b> ' + form.email + '<br>';
                html += '<b>Phone:</b> ' + form.phone + '<br>';
                jQuery('#user_id').val(form.id);
                finance.printMessage(html);
            }



        }, printMessage: function (message) {

            var html = '';
            html += '<div>';
            html += message;
            html += '</div>';
            jQuery('.searched-user-holder').html(html);
        }
    };
}();

