<div id=":tc" class="ii gt adP adO"><div id=":tb" class="a3s aXjCH m15beda8dddfd5b97"><u></u>
        <div>
            <center>
                <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="m_-9193298684132930263bodyTable">
                    <tbody><tr>
                        <td align="center" valign="top" id="m_-9193298684132930263bodyCell">
                            <span style="color:#ffffff;display:none;font-size:0px;height:0px;width:0px">You're almost ready to get started!</span>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tbody><tr>
                                    <td align="center" bgcolor="#52BAD5" valign="top" id="m_-9193298684132930263templateHeader" style="background:linear-gradient(to bottom, #ff8300 0%,#F4743D 100%);color:white;padding-right:30px;padding-left:30px">

                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:700px" class="m_-9193298684132930263emailContainer">
                                            <tbody><tr>
                                                <td align="center" valign="top" id="m_-9193298684132930263logoContainer" style="padding-top:40px;padding-bottom:40px">
                                                    <table style="display: block;width: 100%">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" style="text-align: left;font-size: 2.5rem;font-weight: bold">MT Perevozki</td>
                                                                <td align="right" style="text-align: right;font-size: 1rem;font-weight: bold;width: 400px;color: white;">+373 69 107 853</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody></table>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" bgcolor="#52BAD5" valign="top" id="m_-9193298684132930263headerContainer" style="background:linear-gradient(to bottom, #F4743D 0%,#F74E00 100%);padding-right:30px;padding-left:30px">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody><tr>
                                                <td align="center" valign="top">

                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:640px" class="m_-9193298684132930263emailContainer">
                                                        <tbody><tr>
                                                            <td align="center" valign="top">
                                                                <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%" id="m_-9193298684132930263headerTable" style="background-color:#ffffff;border-collapse:separate;border-top-left-radius:4px;border-top-right-radius:4px">
                                                                    <tbody><tr>
                                                                        <td align="center" valign="top" width="100%" style="padding-top:40px;padding-bottom:0;font-size: 1.5rem;font-weight: bold">{{translate('cargo_added')}}</td>
                                                                    </tr>
                                                                    </tbody></table>
                                                            </td>
                                                        </tr>
                                                        </tbody></table>

                                                </td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top" id="m_-9193298684132930263templateBody">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody><tr>
                                                <td align="center" valign="top">

                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:700px;font-size: 1.2rem;" class="m_-9193298684132930263emailContainer">
                                                        <tbody><tr>
                                                            <td valign="top" width="100%" style="padding-right:70px;padding-left:40px;padding-top: 30px;" id="m_-9193298684132930263bodyContainer">
                                                                <div style="padding: 25px;">
                                                                {{$name}}, {{translate('reg_message_top')}}<br>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        @if(isset($password))
                                                        <tr>
                                                            <td valign="top" width="100%" style="padding-top: 30px;padding-right:30px;padding-left:30px;" id="m_-9193298684132930263bodyContainer">
                                                                <div style="background: #f8f8f8;padding: 25px;">
                                                                    {{translate('use_email')}}:<br>
                                                                    {{$email}}<br>
                                                                    {{translate('and_pass')}}: {{$password}}<br><br>

                                                                    {{translate('do_not_save')}}
                                                                    <br><br>
                                                                    {{translate('we_found')}} <b>{{$count}} {{translate('orders')}}</b> {{translate('transport_from')}} {{$from}}
                                                                    <br><br>
                                                                    <div style="text-align: center">
                                                                        <a href="{{$subscribe_link}}" style="background-color:#ff8300;border-collapse:separate;border-top:20px solid #ff8300;border-right:20px solid #ff8300;border-bottom:20px solid #ff8300;border-left:20px solid #ff8300;border-radius:3px;color:#ffffff;display:inline-block;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:600;letter-spacing:.3px;text-decoration:none" target="_blank">
                                                                            {{translate('show_transport')}}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td valign="top" width="100%" style="padding-right:30px;padding-left:30px;" id="m_-9193298684132930263bodyContainer">
                                                                <div style="background: #deaa5c;padding: 25px;color:white">
                                                                   <div style="text-align: center;font-weight: bold;font-size: 1.5rem;">
                                                                       {{translate('order_details')}}
                                                                   </div>
                                                                    <div>
                                                                        {{translate('from')}}: <b>{{$from}}</b>
                                                                    </div>

                                                                    <div>
                                                                        {{translate('to')}}: <b>{{$to}}</b>
                                                                    </div>

                                                                    <div>
                                                                        {{translate('transport_type')}}: <b>{{$transport}}</b>
                                                                    </div>

                                                                    <div>
                                                                        {{translate('cargo_name')}}: <b>{{$volume}}</b>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody></table>
                                                </td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top" id="m_-9193298684132930263templateFooter" style="padding-right:30px;padding-left:30px">

                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:640px" class="m_-9193298684132930263emailContainer">
                                            <tbody><tr>
                                                <td valign="top" id="m_-9193298684132930263footerContent" style="border-top:2px solid #f2f2f2;color:#484848;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:24px;padding-top:40px;padding-bottom:20px;text-align:center">
                                                    <p style="color:#484848;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:24px;padding:0;margin:0;text-align:center">2009 - {{date('Y')}}<br> &copy;MTPerevozki. All Rights Reserved.

                                                </td>
                                            </tr>
                                            </tbody></table>

                                    </td>
                                </tr>
                                </tbody></table>

                        </td>
                    </tr>
                    </tbody></table>
            </center>

        </div></div></div>