<!doctype html>
<html>
   <head>
      <title>Deposit Calculator</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="/assets/air-datepicker/css/datepicker.min.css" rel="stylesheet">
      <link href="style.css" rel="stylesheet">
      <script src="/assets/jquery-3.6.0.min.js"></script>
      <script src="/assets/jquery-validation/jquery.validate.min.js"></script>
      <script src="/assets/air-datepicker/js/datepicker.min.js"></script>
      <script src="/assets/moment/moment.js"></script>
      <script src="/assets/AutoNumeric.js"></script>
      <script src="/script.js"></script>
   </head>
   <body>
      <nav class="navbar">
         <div class="navbar-container">
            <a class="navbar-brand" href="#"></a>
            <a class="nav-link" href="#">Deposit Calculator</a>
         </div>
      </nav>
      <div class="wrapper">
         <div class="calc-title">Депозитный калькулятор</div>
         <div class="calc-desc">Калькулятор депозитов позволяет расчитать ваши доходы после внесения суммы на счет в банке по определенному тарифу.</div>
         <div class="form-wrapper">
            <form id="calcform" class="needs-validation" method="post">
               <div class="row">
                  <div class="col-6">
                     <div class="input-group">
                        <input id="startDate" name="startDate" type='text' class="form-control datepicker-here" data-position="bottom left" placeholder="20.20.2020" autocomplete="off"/>
                        <label for="startDate" class="form-label">Дата открытия</label>
                     </div>
                     <div class="invalid-feedback"></div>
                  </div>
                  <div class="col-6">
                     <div class="input-group">
                        <div class="form-floating">
                           <input type="text" class="form-control" id="term" name="term" placeholder="1" autocomplete="off"/>
                           <label for="term" class="form-label">Срок вклада</label>
                        </div>
                        <div class="input-group-append ">
                           <select id="term-type" class="form-select">
                              <option value="month" selected>месяц</option>
                              <option value="year">год</option>
                           </select>
                        </div>
                     </div>
                     <div class="invalid-feedback"></div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-6">
                     <div class="input-group">
                        <input type="text" class="form-control" id="sum" name="sum" placeholder="100000"  autocomplete="off"/>
                        <label for="sum" class="form-label">Сумма вклада</label>
                     </div>
                     <div class="invalid-feedback"></div>
                  </div>
                  <div class="col-6">
                     <div class="input-group">
                        <input type="text" class="form-control" id="percent" name="percent" value="10" placeholder="10"  autocomplete="off"/>
                        <label for="percent" class="form-label">Процентная ставка, % годовых</label>
                     </div>
                     <div class="invalid-feedback"></div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-6">
                     <div class="input-group input-group-check">
                        <input id="checkbox_monthly" class="form-check-input" type="checkbox" value=""/>
                        <label class="form-check-label" for="checkbox_monthly">Ежемесячное пополнение вклада</label>
                     </div>
                  </div>
                  <div class="col-6">
                     <div id="monthly_payment" class="input-group" style="display: none;">
                        <input type="text" class="form-control" name="sumAdd" id="sumAdd" value="0" placeholder="0"  autocomplete="off"/>
                        <label for="sumAdd" class="form-label">Сумма пополнения вклада</label>
                     </div>
                     <div class="invalid-feedback"></div>
                  </div>
               </div>
               <div class="row btn-row">
                  <div class="col-12">
                     <div class="input-group">
                        <button type="submit" class="btn-calc">Расчитать</button>
                     </div>
                  </div>
               </div>
               <div id="calc-result-row" class="row" style="display: none;">
                  <div class="col-12">
                     <hr>
                  </div>
                  <div class="col-12">
                     <div class="result-title">Сумма к выплате</div>
                  </div>
                  <div class="col-12 result">
                     <span>₽ </span>
                     <div id="calc-result">250 000</div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </body>
</html>