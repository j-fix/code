  
  <?php
  
  //   СОЗДАНИЕ КЛИНТА
  
  // phone: array[string] - Телефон клиента;
  // ↑ значением ключа phone должна быть строка с номером заключенная в массиве - так написано в API   https://remonline.ru/docs/api/#apisection2
  
  $phone = array('phone'=>79778860657)   // так в коде
  
  // ИЛИ
  
  $phone = array('phone'=> '79778860657')   // так в коде
  
    // ИЛИ
    
    $phone = array('79778860657')   // так в коде
      
    // В ИТОГЕ ПОЛУЧАЕМ
    
    (
    [email] => RumyantsevGT@ya.ru
    [phone] => Array
        (
            [phone] => 79778860657
        )

    [name] => Egor
)
    
    // ИЛИ
    
    (
    [email] => RumyantsevGT@ya.ru
    [phone] => Array
        (
            [0] => 79778860657
        )

    [name] => Egor
)
  
  
  // НО ОТВЕТ ОТ REMONLINE ВСЕГДА ТАКОЙ
  
                      [phone] => Array
                        (
                            [0] => Необходимо ввести номер телефона в формате вашей компании
Необходимо ввести номер телефона в формате вашей компании
Необходимо ввести номер телефона в формате вашей компании
Необходимо ввести номер телефона в формате вашей компании
Необходимо ввести номер телефона в формате вашей компании
                        )
                        
                        */
