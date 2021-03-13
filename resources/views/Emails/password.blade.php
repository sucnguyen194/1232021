<div>
  <table align="center" cellpadding="0" cellspacing="0" style="background-color:#d4eefb;margin:auto;border-collapse:collapse;width:700px;font-family:verdana;color:#222">
    <tbody>
      <tr>
        <td style="padding:20px 30px;width:auto"><table cellpadding="0" cellspacing="0" style="border-collapse:collapse;background-color:white;width:700px;margin:auto">
            <tbody>
              <tr>
                <td style="background-color:#00c0f1">
                    <table cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:100%">
                    <tbody>
                      <tr style="border:1px solid #b3e4fc">

                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr style="border-left:1px solid #b3e4fc;border-right:1px solid #b3e4fc">
                <td height="5"></td>
              </tr>
              <tr style="border-left:1px solid #b3e4fc;border-right:1px solid #b3e4fc;border-bottom:1px solid #b3e4fc">
                <td><table cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:12px">
                    <tbody>
                      <tr>
                        <td style="padding-left:20px;padding-right:20px;padding-bottom:30px;font-family:verdana,arial;padding-top:10px"><h3>Cập nhật mật khẩu</h3>
                          <table>
                            <tbody>
                              <tr>
                                <td>Mật khẩu mới: <strong>{{$code}}</strong> <br>Quý khách vui lòng click vào đường dẫn dưới để cập nhật mật khẩu mới cho tài khoản {{$email}}: <br>
									<a href="{{route('user.reset',['email' => $email, '_token' => $password , 'time' => $time])}}">
									<strong>Cập nhật mật khẩu</strong></a>
								  </td>
                              </tr>
                            </tbody>
                          </table>
						</td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
              <td style="padding:5px; font-size: 11px">Website: <a href="{{route('home')}}" target="_blank">{{$setting->name}} </td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
  <div class="yj6qo"></div>
  <div class="adL"> </div>
</div>
