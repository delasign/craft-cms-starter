/**
 * Mail Service Plugin
 *
 * Index Field JS
 *
 * @author    Oscar de la Hera Gomez
 * @copyright Copyright (c) 2022 delasign
 * @link      www.delasign.com
 * @package   Mail Service
 * @since     1.0.0
 */


(function($){
    MailService = Garnish.Base.extend({
        $elements: null,
        $alertBar: null,
        $sendEmail: null,
        init: function () {
            // Get Items
            this.$sendEmail = document.getElementById("sendEmail");
            this.$alertBar = document.getElementById("alertBar");
            this.$elements = document.getElementsByClassName("emailCheckbox");

            // Create a listener for change events in the email checkbox
            this.addListener(this.$elements, 'change', 'onCheckboxValueChange');
            // Create events for buttons
            this.addListener(this.$sendTest, 'click', 'onPressTestSend');
            this.addListener(this.$sendEmail, 'click', 'onPressSend');

             
        }, onCheckboxValueChange: function(e) {
            switch (this.getNumberOfCheckedItems()) {
                case 1:
                    this.$sendEmail.setAttribute("class", "btn submit");
                    this.$alertBar.setAttribute("class", "alertBar hidden");
                    $
                    break;
                default:
                    this.$sendEmail.setAttribute("class", "disabled btn submit");
                    this.$alertBar.setAttribute("class", "alertBar");
                    break
            }
        }, getNumberOfCheckedItems: function () {
            var numberOfCheckedItems = 0;
            const inputs = document.getElementsByClassName("emailCheckbox");
            for (var index = 0; index < inputs.length; index++) {
                if (inputs[index]["checked"]) {
                    numberOfCheckedItems += 1;
                }
            }
            return numberOfCheckedItems
        }, onPressSend: function (e) {
            if (this.getNumberOfCheckedItems() === 1) {
                console.log("PRESSED SEND : ", this.getSelectedEmailEntryId());
                window.location.href = "/?p=admin/actions/craftcmsstarterplugin/sample/sample-invocation&id=" + this.getSelectedEmailEntryId();
            }
        }, getSelectedEmailEntryId: function () {
            const inputs = document.getElementsByClassName("emailCheckbox");
            for (var index = 0; index < inputs.length; index++) {
                if (inputs[index]["checked"]) {
                    console.log("RETURNING ENTRY WITH ID : " + inputs[index]["name"]);
                    return inputs[index]["name"]
                }
            }
        }
    });
})(jQuery);