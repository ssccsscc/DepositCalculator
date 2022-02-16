
const autoNumericOptionsInput = {
    allowDecimalPadding: false,
    digitGroupSeparator : ' ',
};
const autoNumericOptionsOutput = {
    allowDecimalPadding: true,
    decimalPlaces: 2,
    digitGroupSeparator : ' ',
};

function prepareNumber(num)
{
    return num.split(' ').join('').trim();
}

function activateValidation()
{
    jQuery.validator.addMethod("checkTerm", function(value, element) {
        if($('#term-type option:selected').val() == "month"){
            return value >= 1 && value <= 60
        }else{
            return value >= 1 && value <= 5
        }
    }, "");

    jQuery.validator.addMethod("dateFormat", function(value, element) {
        return moment(value, 'DD.MM.YYYY',true).isValid()
    }, "");

  $("#calcform").validate({
    rules: {
        startDate: {
            required: true,
            dateFormat: true
        },
        term:  {
            required: true,
            checkTerm: true
        },
        sum: {
            required: true,
            min: 1000,
            max: 3000000,
            normalizer: prepareNumber
        },
        percent: {
            required: true,
            min: 3,
            max: 100,
            digits: true
        },
        sumAdd :  {
            required: true,
            min: 0,
            max: 3000000,
            normalizer: prepareNumber
        },
    },
    messages: {
        startDate: {
            required: "Введите дату открытия вклада",
            dateFormat: "Введите дату в формате ДД/ММ/ГГГГ"
        },
        term: {
            required: "Введите срок вклада",
            checkTerm: "Срок должен составлять от 1 до 60 месяцев (или 1-5 лет)"
        },
        sum: {
            required: "Введите сумму вклада",
            min: "Сумма должна быть от 1 000 до 3 000 000",
            max: "Сумма должна быть от 1 000 до 3 000 000",
        },
        percent: {
            required: "Введите процентную ставку",
            min: "Процентная ставка должна быть целым числом от 3 до 100",
            max: "Процентная ставка должна быть целым числом от 3 до 100",
            digits: "Процентная ставка должна быть целым числом от 3 до 100"
        },
        sumAdd: {
            required: "Введите срок вклада",
            min: "Сумма пополнения должна быть от 0 до 3 000 000",
            max: "Сумма пополнения должна быть от 0 до 3 000 000",
        },
    },
    errorPlacement: function(error,element) {
        $(element).closest(".input-group").siblings(".invalid-feedback").html(error)
    },
    highlight: function (element) { 
        $(element).addClass("is-invalid").removeClass("is-valid");
        $(element).closest(".input-group").siblings(".invalid-feedback").show()
    },
    unhighlight: function (element) {
        $(element).addClass("is-valid").removeClass("is-invalid");
        $(element).closest(".input-group").siblings(".invalid-feedback").hide()
    }
  });
}

function checkbox_showhide_changed(checkbox, block){
    if(checkbox.is(":checked"))
    {
        block.show()
    }else{
        block.hide()
    }
}

function initCheckbox(){
    var checkBox = $('#checkbox_monthly')
    var inputblock = $('#monthly_payment')
    
    checkbox_showhide_changed(checkBox, inputblock)

    checkBox.change(function() {
        checkbox_showhide_changed(checkBox, inputblock)  
    });
}

function submitForm(form){
    $('#calc-result-row').hide();
    if(!form.valid()){
        return
    }
    var termInMonths = parseInt(form.find("#term").val())
    if (form.find('#term-type option:selected').val() == "year"){
        termInMonths = termInMonths * 12
    }
    var _sumAdd = 0
    if (form.find('#checkbox_monthly').is(":checked")){
        _sumAdd = parseInt(prepareNumber(form.find("#sumAdd").val()))
    }
    var toApi = {
        startDate: form.find("#startDate").val(),
        sum: parseInt(prepareNumber(form.find("#sum").val())),
        term: termInMonths,
        percent: parseInt(form.find("#percent").val()),
        sumAdd: _sumAdd
    }
    console.log(toApi)
    $.ajax({
        url: '/calc.php',
        method: 'post',
        data: JSON.stringify(toApi),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data){
            console.log(data)
            $('#calc-result').html(AutoNumeric.format(data.sum, autoNumericOptionsOutput));
            $('#calc-result-row').show();
        },
        error: function (jqXHR, exception) {
            console.log(exception)
            $('#calc-result').html("Ошибка, попробуйте позже");
            $('#calc-result-row').show();
        }
    });
}

function initOnFormSubmit(){
    $("#calcform").on("submit", function(e){
        e.preventDefault();
        try {
            submitForm($(this))
        } catch (error) {
            console.log(error)
            $('#calc-result').html("Ошибка, попробуйте позже");
            $('#calc-result-row').show();
        }
    });
}

function enableInputNumbersFormatting(){
    new AutoNumeric('#sum', autoNumericOptionsInput);
    new AutoNumeric('#sumAdd', autoNumericOptionsInput);
}

$( document ).ready(function () {
    'use strict'

    enableInputNumbersFormatting()

    initCheckbox()

    activateValidation()

    initOnFormSubmit()

  })
