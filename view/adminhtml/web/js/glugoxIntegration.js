/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/*jshint jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";


    $.widget('mage.glugoxIntegration', {
        /**
         * Options common to all instances of this widget.
         * @type {Object}
         */
        options: {
            /**
             * URL of the integration grid.
             * @type {String}
             */
            gridUrl: ''
        },
        /**
         * Bind event handler for the action when admin clicks "Save & Activate" button.
         * @private
         */
        _create: function () {
            var that = this;
            if ($('#save-split-button-activate').length) {
                // We're on the "New integration" page - bind related handler
                this._form = $('#edit_form');
                this._form.on('saveAndActivate', $.proxy(this._saveAndActivate, this));
            }
        },
        /**
         * Save new integration, then kick off the activate dialog.
         * @private
         */
        _saveAndActivate: function () {
            if (this._form.validation && !this._form.validation('isValid')) {
                return false;
            }

            $.ajax({
                url: this._form.prop('action'),
                type: 'post',
                data: this._form.serialize(),
                dataType: 'json',
                context: this,
                beforeSend: function () {
                    $('body').trigger('processStart');
                },
                success: function (data) {
                    if (data['_redirect']) {
                        window.location.href = data['_redirect'];
                    } else if (data['integrationId']) {
                        var integrationName = $('#integration_properties_name').val();
                        alert("Name : " + integrationName);

                    }
                },
                error: function (jqXHR, status, error) {
                    alert({
                        content: $.mage.__('Sorry, something went wrong. Please try again later.')
                    });
                    window.console && console.log(status + ': ' + error + "\nResponse text:\n" + jqXHR.responseText);
                },
                complete: function () {
                    $('body').trigger('processStop');
                }
            });

            return true;
        }

    });


    window.glugoxIntegration = {
        /**
         * Options
         * @type {Object}
         */
        options: {
            /**
             * URL of the integration ajax.
             * @type {String}
             */
            ajaxUrl: '',
            monitorLocator: '.glugox-integration-monitor',
        },
        /**
         *
         * @returns {undefined}
         */
        run: function (ajaxUrl) {
            this.options.ajaxUrl = ajaxUrl;
            this._run();
        },
        /**
         *
         * @returns {undefined}
         */
        _run: function () {
            console.log("GlugoxIntegration._run : " + this.options.ajaxUrl);
            $("#g-integration-run-button").prop('disabled', true);
            if ($(this.options.monitorLocator).length) {
                $(this.options.monitorLocator).glugoxIntegrationMonitor("wakeUp");
            }
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'get',
                data: '',
                dataType: 'json',
                context: this,
                beforeSend: function () {
                    $('body').trigger('processStart');
                },
                success: function (data) {
                    if (data['_redirect']) {
                        window.location.href = data['_redirect'];
                    }
                },
                error: function (jqXHR, status, error) {
                    alert({
                        content: $.mage.__('Sorry, something went wrong. Please try again later.')
                    });
                    window.console && console.log(status + ': ' + error + "\nResponse text:\n" + jqXHR.responseText);
                },
                complete: function () {
                    $('body').trigger('processStop');
                }
            });
        },
    }

});