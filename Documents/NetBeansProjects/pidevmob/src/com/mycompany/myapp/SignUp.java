/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp;

import com.codename1.ui.Button;
import com.codename1.ui.Dialog;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.mycompany.services.UserService;

/**
 * GUI builder created Form
 *
 * @author Lotfi
 */
public class SignUp extends Form {
TextField tf_nom,tf_prenom,tf_email,tf_password,tf_role,tf_country,tf_photo;
    public SignUp() {
        setTitle("Inscription");
        setLayout(BoxLayout.y());
         tf_nom =new TextField("", "Votre Nom");
         tf_prenom =new TextField("", "Votre Prenom");
         tf_email =new TextField("", "Votre mail");
         tf_password =new TextField("", "Votre mot de passe");
         tf_role =new TextField("", "Votre role");
         tf_country =new TextField("", "Votre pays");
         tf_photo =new TextField("", "Votre photo");

        

       
        Button btnback = new Button("back");
         
        btnback.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                new Login().showBack();
            }
        });
        Button btnRegistre=new Button("ins");
        btnRegistre.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                UserService us =new UserService();
                us.signup(tf_nom, tf_nom, tf_email, tf_password, tf_role, tf_country, tf_photo);
            }
        });
        addAll(tf_nom,tf_prenom,tf_email,tf_password,tf_role,tf_country,tf_photo,btnback,btnRegistre);
        
        
    }
    
    
}
