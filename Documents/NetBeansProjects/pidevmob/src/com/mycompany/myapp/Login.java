/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp;

import com.codename1.io.ConnectionRequest;
import com.codename1.ui.Button;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.util.Resources;
import com.mycompany.services.UserService;

/**
 * GUI builder created Form
 *
 * @author Lotfi
 */
public class Login extends Form {
    TextField tf_email,tf_password ;
    public Login() {
          setTitle("Inscription");
        setLayout(BoxLayout.y());
         tf_email=new TextField("","Email");
         tf_password=new TextField("","Password");
        Button btnlogin=new Button("Login");
        Button btnreg= new Button(" Don't have an account ?SignUp...");
        
        btnlogin.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                UserService us=new UserService();
                us.signin(tf_email,tf_password);
               
            }
        });
        btnreg.addActionListener(new ActionListener() {
              @Override
              public void actionPerformed(ActionEvent evt) {
                new SignUp().show();
                  
              }
          });
        addAll(tf_email,tf_password,btnlogin,btnreg);
    }

  
    
   

}
