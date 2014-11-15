15.11.2014:

Поменялась структура ответа всех методов. Если раньше возвращалось data, то теперь - {response: data, serverTime: %timestamp%}

/autopayment/create
Параметры:
  accountId
  startDate - timestamp с какого времени начинать платить, по умолчанию - время сервера
  period - day, week, month, year
  type - тип платежа, erip или direct
  data - все поля, которые передаются в /payment/pay или /erip/pay при type=erip или type=direct соответственно

$ curl -s -X POST -d clientId=31 -d accountId=23 -d startDate='1416043413' -d period=week -d 'data[recipientBank]=a&data[payerName]=w&data[code]=e&data[recipientAccountId]=24&data[recipientName]=r&data[amount]=1' -d type=director kofi.local/api/autopayment/create; echo ''
{"response":{"success":false,"errors":{"type":["\"type\" parameter should be of values erip or direct"]}},"serverTime":1416046920}
$ curl -s -X POST -d clientId=31 -d accountId=23 -d startDate='1416043413' -d period=week -d 'data[recipientBank]=a&data[payerName]=w&data[code]=e&data[recipientAccountId]=24&data[recipientName]=r&data[amount]=1' -d type=direct kofi.local/api/autopayment/create; echo ''
{"response":{"success":true,"id":4},"serverTime":1416047841}

/autopayment/list
$ curl -s -X POST -d clientId=31 -d accountId=23 kofi.local/api/autopayment/list; echo ''
{"response":[{"id":2,"startDate":1416032613,"period":"week","type":"erip","data":"asd"},{"id":3,"startDate":1416032613,"period":"week","type":"erip","data":{"recipientBank":"a","payerName":"w","code":"e","recipientAccountId":"24","recipientName":"r","amount":"1"},"lastPayment":1415912400},{"id":4,"startDate":1416032613,"period":"week","type":"erip","data":{"recipientBank":"a","payerName":"w","code":"e","recipientAccountId":"24","recipientName":"r","amount":"1"}}],"serverTime":1416048154}

/autopayment/{id}/update
Параметры такие же, как в методе /autopayment/create
$ curl -s -X POST -d clientId=31 -d accountId=23 -d startDate='1416043413' -d period=week -d 'data[recipientBank]=b&data[payerName]=w&data[code]=e&data[recipientAccountId]=24&data[recipientName]=r&data[amount]=3' -d type=erip kofi.local/api/autopayment/4/update; echo ''
{"response":{"success":true,"id":4},"serverTime":1416048411}

/autopayment/{id}/delete
$ curl -s -X POST -d clientId=31 -d accountId=23 kofi.local/api/autopayment/2/delete; echo ''{"response":{"success":true},"serverTime":1416048480}

Notification:
type: newDeposit
data: initialAmount, initialCurrency, accountAmount, accountCurrency

======

10.11.2014:

/currency/rates - rate => buyRate, sellRate

======

09.11.2014:

/currency/rates - вместо поля name в ответе nameLocalized - объект с ключами en, ru, ...

/payment/report - выписка. Пареметры:
  accountId
  dateFrom (необязательный)
  dateTo (необязательный)
  type: erip|payment (необязательный)
Пример ответа:
[{"type":"erip","amount":-1,"paymentInfo":{"payment":"Water","fields":{"address":"asd"}},"processedAt":1415529109,"giverAccountId":23,"giverFirstName":"Kipp","giverLastName":"Ayres"},{"type":"direct","amount":-1,"paymentInfo":{"recipientBank":"\u0413\u0430\u043c\u043c\u0430 \u0415-\u0411\u0430\u043d\u043a","payerName":"w","code":"e","recipientAccountId":"24","recipientName":"r","amount":"1"},"processedAt":1415529170,"recipientAccountId":24,"recipientFirstName":"Kipp","recipientLastName":"Ayres","giverAccountId":23,"giverFirstName":"Kipp","giverLastName":"Ayres"},{"type":"direct","amount":-1,"paymentInfo":{"recipientBank":"\u0413\u0430\u043c\u043c\u0430 \u0415-\u0411\u0430\u043d\u043a","payerName":"w","code":"e","recipientAccountId":"24","recipientName":"r","amount":"1"},"processedAt":1415529298,"recipientAccountId":24,"recipientFirstName":"Kipp","recipientLastName":"Ayres","giverAccountId":23,"giverFirstName":"Kipp","giverLastName":"Ayres"}]

======

01.11.2014:

/currency/rates - информация о курсах валют и валютах

======

/client/list/{id} - информация о клиенте

Во все методы методы дальше нужно передавать clientId

/client/auth - авторизация
параметры: password

/client/changePassword - авторизация
параметры: password

/erip/tree

/erip/pay - пример параметров accountId=23&paymentId=1&amount=1&fields[address]=q

/payment/pay - Платеж по реквизитам
Параметры: 'accountId=23&recipientBank=%D0%93%D0%B0%D0%BC%D0%BC%D0%B0+%D0%95-%D0%91%D0%B0%D0%BD%D0%BA&payerName=w&code=e&recipientAccountId=24&recipientName=r&amount=1'
Если recipientBank == 'Гамма Е-Банк', то на счет recipientAccountId будет начислено amount бабла