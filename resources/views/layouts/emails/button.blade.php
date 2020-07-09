@if(isset($actionLink))
    <tr>
        <td align="center" valign="top">
            <!-- CENTERING TABLE // -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr style="padding-top:0;">
                    <td align="center" valign="top">
                        <!-- FLEXIBLE CONTAINER // -->
                        <table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
                            <tr>
                                <td style="padding-top:0;padding-bottom:54px;" align="center" valign="top" width="500"
                                    class="flexibleContainerCell">

                                    <!-- CONTENT TABLE // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="80%" class="emailButton"
                                           style="background-color: #ee7e08;">
                                        <tr>
                                            <td align="center" valign="middle" class="buttonContent"
                                                style="padding-top:15px;padding-bottom:15px;padding-right:15px;padding-left:15px;">
                                                <a style="color:#FFFFFF;text-decoration:none;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:bold!important;line-height:135%;"
                                                   href="{{$actionLink}}" target="_blank">{{$actionText}}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // CONTENT TABLE -->

                                </td>
                            </tr>
                        </table>
                        <!-- // FLEXIBLE CONTAINER -->
                    </td>
                </tr>
            </table>
            <!-- // CENTERING TABLE -->
        </td>
    </tr>
@endif