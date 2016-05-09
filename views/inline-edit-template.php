<form method="get">
    <table style="display: none">
        <tbody id="inlineedit">
            <?php
                $wp_user_list_table = _get_list_table('WP_Users_List_Table');
                $column_count = $wp_user_list_table->get_column_count();
            ?>
            <tr id="inline-edit" class="inline-edit-row inline-edit-row-user inline-edit-user quick-edit-row quick-edit-row-user inline-edit-user" style="display:none">
                <td colspan="<?php echo $column_count; ?>" class="colspanchange">
                    <fieldset class="inline-edit-col-left">
                        <legend class="inline-edit-legend"><?php _e( 'Quick Edit', 'wpu-quick-edit' ); ?></legend>

                        <div class="inline-edit-col">
                            <label class="">
                                <span class="title"><?php _e( 'Email', 'wpu-quick-edit' ); ?></span>
                                <span class="input-text-wrap">
                                    <input type="text" name="email" class="ptitle" value=""></span>
                            </label>

                            <label class="">
                                <span class="title"><?php _e( 'First Name', 'wpu-quick-edit' ); ?></span>
                                <span class="input-text-wrap"><input type="text" name="first_name" class="ptitle" value=""></span>
                            </label>

                            <label class="">
                                <span class="title"><?php _e( 'Last Name', 'wpu-quick-edit' ); ?></span>
                                <span class="input-text-wrap"><input type="text" name="last_name" class="ptitle" value=""></span>
                            </label>

                            <label class="">
                                <span class="title"><?php _e( 'Nickname', 'wpu-quick-edit' ); ?></span>
                                <span class="input-text-wrap"><input type="text" id="nickname" name="nickname" class="ptitle" value=""></span>
                            </label>

                            <label class="">
                                <span class="title"><?php _e( 'Role', 'wpu-quick-edit' ); ?></span>
                                <span class="input-text-wrap">
                                    <select name="role" id="role" class="select-field">
                                        <?php
                                            wp_dropdown_roles();
                                        ?>
                                    </select>
                                </span>
                            </label>
                        </div>

                    </fieldset>

                    <fieldset class="inline-edit-col-center">
                        <div class="inline-edit-col">
                            <label class="inline-edit-tags user-display-name-lable">
                                <span class="title"><?php _e( 'Display Name publicly as', 'wpu-quick-edit' ) ?></span>
                                <select name="display_name" id="display_name">

                                </select>
                            </label>

                            <label class="">
                                <span class="title"><?php _e( 'Website', 'wpu-quick-edit' ); ?></span>
                                <span class="input-text-wrap"><input type="url" id="url" name="url" class="ptitle" value=""></span>
                            </label>

                        </div>
                    </fieldset>

                    <fieldset class="inline-edit-col-right">
                        <label class="inline-edit-tags">
                            <span class="title"><?php _e( 'Biographical Info', 'wpu-quick-edit' ) ?></span>
                            <textarea cols="22" rows="1" name="description" class="" autocomplete="off"></textarea>
                        </label>

                        <div class="inline-edit-group wp-clearfix">
                            <label class="alignleft">
                                <input type="checkbox" name="rich_editing" value="false">
                                <span class="checkbox-title"><?php _e( 'Disable the visual editor when writing', 'wpu-quick-edit' ); ?></span>
                            </label>

                            <label class="alignleft">
                                <input type="checkbox" name="comment_shortcuts" value="true">
                                <span class="checkbox-title"><?php _e( 'Enable keyboard shortcuts for comment moderation', 'wpu-quick-edit' ); ?></span>
                            </label>

                            <label class="alignleft">
                                <input type="checkbox" name="admin_bar_front" value="1">
                                <span class="checkbox-title"><?php _e( 'Show Toolbar when viewing site', 'wpu-quick-edit' ); ?></span>
                            </label>
                        </div>

                    </fieldset>

                    <?php do_action( 'wp_user_quick_edit_custom_fields' ); ?>

                    <p class="submit inline-edit-save">
                        <?php do_action( 'wp_user_quick_edit_form_submit' ); ?>
                        <button type="button" class="button-secondary cancel alignleft"><?php _e( 'Cancel', 'wpu-quick-edit' ) ?></button>
                        <button type="button" class="button-primary save alignright"><?php _e( 'Update', 'wpu-quick-edit' ) ?></button>
                        <span class="spinner"></span>
                        <span class="error" style="display:none"></span>
                        <br class="clear">
                    </p>

                </td>
            </tr>
        </tbody>
    </table>
</form>