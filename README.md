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

Во все методы методы дальше нужно передавать clientId, в методы

/client/auth - авторизация
параметры: password

/client/changePassword - авторизация
параметры: password

/erip/tree

/erip/pay - пример параметров accountId=23&paymentId=1&amount=1&fields[address]=q

/payment/pay - Платеж по реквизитам
Параметры: 'accountId=23&recipientBank=%D0%93%D0%B0%D0%BC%D0%BC%D0%B0+%D0%95-%D0%91%D0%B0%D0%BD%D0%BA&payerName=w&code=e&recipientAccountId=24&recipientName=r&amount=1'
Если recipientBank == 'Гамма Е-Банк', то на счет recipientAccountId будет начислено amount бабла