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