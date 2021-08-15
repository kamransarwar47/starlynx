        <table border="0" width="400" cellpadding="0" cellspacing="0" align="center">
        	<tr>
            	<td class="top_left"></td>
                <td class="bg_title"><img src="images/lock.png" width="15" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Authorized Access Only</td>
                <td class="top_right"></td>
            </tr>
            <tr>
            	<td class="border_left"></td>
                <td>
                	<?=showError()?>
                	<form action="auth.php" method="post">
                	<p class="box_title">Please sign in</p>
                    <p>
                    	<strong>User Name</strong><br />
                        <input type="text" size="30" name="username" id="username" value="" />
                    </p>
                    
                    <p>
                    	<strong>Password</strong><br />
                        <input type="password" size="30" name="passwd" id="passwd" value="" />
                    </p>
                    
                    <p>
                        <input type="submit" value="Sign in" />
                    </p>
                    </form>
                </td>
                <td class="border_right"></td>
            </tr>
            <tr>
            	<td class="bottom_left"></td>
                <td class="border_bottom"></td>
                <td class="bottom_right"></td>
            </tr>
        </table>
        <script type="text/javascript">
			$("#username").focus();
		</script>