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


    $.widget('mage.glugoxIntegrationMonitor', {
        /**
         * Options common to all instances of this widget.
         * @type {Object}
         */
        options: {
            /**
             * URL of the monitor data.
             * @type {String}
             */
            monitorUrl: '',
            progress: 0,
            updateFrequency: 3000
        },
        /**
         * Bind event handler for the action when admin clicks "Save & Activate" button.
         * @private
         */
        _create: function () {
            this._update();
        },
        /**
         * Wake up!
         * @public
         */
        wakeUp: function () {
            this._log("Wake up!");
        },
        /**
         * Sleep!
         * @public
         */
        sleep: function () {
            this._log("Sleep!");
        },
        /**
         * Updates monitor data after 'updateFrequency' time
         * @private
         */
        _updateLater: function () {
            var that = this;
            setTimeout(function () {
                that._update();
            }, that.options.updateFrequency)
        },
        /**
         * Update monitor with new data.
         * @private
         */
        _update: function () {
            var that = this;
            $.ajax({
                url: this.options.monitorUrl,
                type: 'get',
                data: {},
                dataType: 'json',
                context: that,
                beforeSend: function () {
                    //
                },
                success: function (data) {
                    //
                },
                error: function (jqXHR, status, error) {
                    that._log(status + ': ' + error + "\nResponse text:\n" + jqXHR.responseText);
                },
                complete: function () {
                    //that._updateLater();
                }
            });
        },
        /**
         * Internal log.
         * @private
         */
        _log: function (msg) {
            window.console && console.log(msg);
        }
    });


    return $.mage.glugoxIntegrationMonitor;


});