<?php
phpinfo();

?>


105932
    
105938


12/30
ADJ 53
55
56
57

            If (MaskedTextBox1.Text <= "06:30") Then ' Or (MaskedTextBox1.Text >= "18:00")
                dincentive = bltot * 20.0 / 100
            ElseIf (MaskedTextBox1.Text <= "08:00") Then
                dincentive = bltot * 17.5 / 100
            ElseIf (MaskedTextBox1.Text <= "15:30") Then
                If (txtTimeOFwork.Text = "N") Then if time les than 15 hours
                    dincentive = bltot * 13.0 / 100  
                ElseIf (txtTimeOFwork.Text = "Y") Then
                    dincentive = bltot * 15.0 / 100
                End If
            ElseIf (MaskedTextBox1.Text <= "18:00") Then
                dincentive = bltot * 20.0 / 100              
            ElseIf (MaskedTextBox1.Text >= "18:01") Then
                dincentive = bltot * 13.0 / 100
            End If


114831

