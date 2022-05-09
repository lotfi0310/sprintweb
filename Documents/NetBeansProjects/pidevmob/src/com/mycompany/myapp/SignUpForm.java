/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp;

import com.codename1.ui.Button;
import com.codename1.ui.ComboBox;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.TextField;
import com.codename1.ui.Toolbar;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.layouts.FlowLayout;
import com.codename1.ui.plaf.UIManager;
import com.codename1.ui.util.Resources;
import com.mycompany.services.UserService;

/**
 * GUI builder created Form
 *
 * @author Lotfi
 */
public class SignUpForm extends Form {
Resources theme; 
String type; 
    public SignUpForm(Resources theme) {
 super(new BorderLayout(BorderLayout.CENTER_BEHAVIOR_CENTER_ABSOLUTE));
        setUIID("LoginForm");
        Container welcome = FlowLayout.encloseCenter(
                new Label("SignUp", "SignWhite")  
        );
        
        getTitleArea().setUIID("Container");
        
        Toolbar tb=getToolbar();
          TextField nom = new TextField("", "Nom...", 20, TextField.TEXT_CURSOR) ;
        TextField prenom =new TextField("","prenom...",20,TextField.TEXT_CURSOR);
        TextField email = new TextField("", "Email...", 50, TextField.EMAILADDR) ;
        TextField password = new TextField("", "Password...", 20, TextField.PASSWORD) ;
        TextField country =new TextField("", "Pays...", 20, TextField.TEXT_CURSOR);
        TextField num =new TextField("", "Tel...", 15, TextField.PHONENUMBER);

        TextField photo =new TextField("", "photo...", 20, TextField.TEXT_CURSOR);
        ComboBox<String> combo=new ComboBox<>("ROLE_USER","ROLE_FOURNISSEUR","ROLE_ADMIN");
        combo.setUIID("comb");
        nom.setUIID("nom");
        email.setUIID("email");
        prenom.setUIID("prenom");
        password.setUIID("password");
        photo.setUIID("photo");
        country.setUIID("country");
        
        Button btnback = new Button("back");
        Button btnRegistre=new Button("Inscription");

        btnback.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                new LoginForm(theme).showBack();
            }
        });
        btnRegistre.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                UserService us =new UserService();
                 
                us.signup(nom, prenom,email, password,combo,country,num,photo);
            }
        });
    
        // We remove the extra space for low resolution devices so things fit better
        Label spaceLabel;
        if(!Display.getInstance().isTablet() && Display.getInstance().getDeviceDensity() < Display.DENSITY_VERY_HIGH) {
            spaceLabel = new Label();
        } else {
            spaceLabel = new Label(" ");
        }
        
        
        
        Container by = BoxLayout.encloseY(
                welcome,
                spaceLabel,
                nom,
                prenom,
                email,
                password,
                combo,
                 country,
                 num,
                 photo,
                btnback,
                btnRegistre
                
        );
        add(BorderLayout.CENTER, by);
        
        by.setScrollableY(true);
        by.setScrollVisible(false);
    }
        
        
    
        
        
    
    
    
}
