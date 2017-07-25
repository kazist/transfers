/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {

    block_edit_view.addEvents(jQuery('body'));
});

block_edit_view = function () {
    return {
        addEvents: function (html) {
            html.find('#all_applications').next().on('click', function () {
                block_edit_view.addAllBasedOnSelected(jQuery(this), 'single_application');
            });

            html.find('#all_components').next().on('click', function () {
                block_edit_view.addAllBasedOnSelected(jQuery(this), 'single_component');
            });

            html.find('#all_subsets').next().on('click', function () {
                block_edit_view.addAllBasedOnSelected(jQuery(this), 'single_subset');
            });

            html.find('.path_select_all').on('click', function () {
                block_edit_view.addSelectAll(jQuery(this));
            });

            html.find('.path_toggle').on('click', function () {
                block_edit_view.toggleSelection(jQuery(this));
            });

            html.find('.path_clear').on('click', function () {
                block_edit_view.clearSelection(jQuery(this));
            });
        },
        addAllBasedOnSelected: function (this_element, class_name) {
            var parent_div = this_element.parent();
            var status = (parent_div.hasClass('checked')) ? true : false;
            var selectedlist = jQuery('.' + class_name);

            block_edit_view.changeStatus(selectedlist, status);
        },
        addSelectAll: function (this_element) {
            var selectedlist = this_element.closest('.form-field-group').find('input');
            block_edit_view.changeStatus(selectedlist, true);
        },
        toggleSelection: function (this_element) {

            var checked_selectedlist = this_element.closest('.form-field-group').find('input:checked');
            var unchecked_selectedlist = this_element.closest('.form-field-group').find('input');


            if (checked_selectedlist.length) {
                block_edit_view.changeStatus(unchecked_selectedlist, true);
                block_edit_view.changeStatus(checked_selectedlist, false);
            } else {
                block_edit_view.changeStatus(unchecked_selectedlist, true);
            }

        },
        clearSelection: function (this_element) {
            var selectedlist = this_element.closest('.form-field-group').find('input');
            block_edit_view.changeStatus(selectedlist, false);
        },
        changeStatus: function (selectedlist, status) {
            selectedlist.each(function () {
                var selected = jQuery(this);
                var closest_div = selected.closest('div');

                selected.prop('checked', status);

                if (status) {
                    closest_div.addClass('checked');
                } else {
                    closest_div.removeClass('checked');
                }
            });
        }

    };
}();