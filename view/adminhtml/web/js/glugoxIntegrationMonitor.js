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
            updateFrequency: 200
        },
        /**
         * Log messages from the server
         */
        messages: [],
        /**
         * Last message datetime
         */
        lastMsgTime: null,
        /**
         * Pushed log ids
         */
        pushedLogIds: [],
        /**
         * Alias messages references
         */
        aliasMessages: [],
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
            var monitor = $(".glugox-integration-monitor-data");
            $.ajax({
                url: this.options.monitorUrl,
                type: 'post',
                data: {form_key: window.FORM_KEY, fromTime: that.lastMsgTime},
                dataType: 'json',
                context: that,
                beforeSend: function () {
                    //
                },
                success: function (data) {

                    $.each(data, function (i, o) {

                        if (o == "delete-all") {
                            that.pushedLogIds = [];
                            that.messages = [];
                            that.lastMsgTime = null;
                            monitor.html("");

                        } else {
                            var id = parseInt(o.log_id);
                            if ($.inArray(id, that.pushedLogIds) == -1) {
                                that.messages.push(o);
                                that.lastMsgTime = o.created_at;
                                that.pushedLogIds.push(id);
                                console.log(that.pushedLogIds);
                                if (o.log_alias) {
                                    that.aliasMessages[o.log_alias] = o;
                                }
                            } else {
                                if (o.log_alias) {
                                    that.aliasMessages[o.log_alias] = o;
                                    that.lastMsgTime = o.created_at;
                                }
                            }
                        }
                    });

                    var msgHtml = "";
                    $.each(that.messages, function (i, o) {
                        if (o.log_alias) {
                            o = that.aliasMessages[o.log_alias];
                        }
                        msgHtml += '<p><span style="color:#aaaaaa">' + o.created_at.split(" ")[1] + ' >> </span>  ' + o.log_text + '</p>';
                    });
                    monitor.html(msgHtml);
                    $(".glugox-integration-monitor").animate({
                        scrollTop: $(monitor).height()
                    },
                    1500);

                },
                error: function (jqXHR, status, error) {
                    that._log(status + ': ' + error + "\nResponse text:\n" + jqXHR.responseText);
                },
                complete: function () {
                    that._updateLater();
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