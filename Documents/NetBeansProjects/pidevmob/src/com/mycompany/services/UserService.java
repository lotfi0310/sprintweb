/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.services;

import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.ComboBox;
import com.codename1.ui.Dialog;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.util.Resources;
import com.mycompany.Utils.SessionManager;
import com.mycompany.entities.User;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Vector;
import org.json.JSONObject;

/**
 *
 * @author Lotfi
 */
public class UserService {
    
    
  //singleton 
    public static UserService instance = null ;
    
    public static boolean resultOk = true;

    //initilisation connection request 
    private ConnectionRequest req;
    
    public static UserService getInstance() {
        if(instance == null )
            instance = new UserService();
        return instance ;
    }
    
    
    
    public UserService() {
        req = new ConnectionRequest();
        
    }
     
    //Signup
    public void signup(TextField nom,TextField prenom,TextField email,TextField password, TextField roles ,TextField country,TextField photo ) {
        
     
        boolean valide=false; 
        boolean etat=true; 
        String url = "http://localhost:8000/inscription?nom="+nom.getText().toString()+"&prenom="+prenom.getText().toString()+"&email="+email.getText().toString()+"&passwd="+password.getText().toString()+"&role="+roles.getText().toString()+"&country="+country.getText().toString()+"&photo="+photo.getText().toString()+"&valide="+valide+"&etat="+etat;
        req = new ConnectionRequest(url,false); 

       
        //Control saisi
        if(nom.getText().equals(" ")&& prenom.getText().equals(" ")&& password.getText().equals(" ") && email.getText().equals(" ")&&photo.getText().equals(" ")&&country.getText().equals(" ")) {
            
            Dialog.show("Erreur","Veuillez remplir les champs","OK",null);
            
        }
        
        //hethi wa9t tsir execution ta3 url 
        req.addResponseListener((e)-> {
         
            
            byte[]data = (byte[]) e.getMetaData();
            String responseData = new String(data);
            
            System.out.println("data ===>"+responseData);
        }
        );
        
        
        //ba3d execution ta3 requete ely heya url nestanaw response ta3 server.
        NetworkManager.getInstance().addToQueueAndWait(req);
        
            
        
    }
  
    //SignIn
    
    public void signin(TextField email,TextField password ) {
        
        
         String url = "http://localhost:8000/loginmob/"+email.getText().toString()+"/"+password.getText().toString();
        req = new ConnectionRequest(url,false); 
      
        
        req.addResponseListener((NetworkEvent e) -> {
            JSONParser j=new JSONParser();
            String json = new String(req.getResponseData());
            System.out.println(json);
            
            
            try {
                
                Map<String,Object> userlistjson = j.parseJSON(new CharArrayReader(json.toCharArray()));
                if(email.getText().isEmpty()){
                Dialog.show("Champ vide ","saisir votre email pour se connecter ","OK",null);
                }
                 if(password.getText().isEmpty()){
                Dialog.show("Champ vide ","saisir votre password pour se connecter ","OK",null);
                }
               
                
                if(userlistjson.size()!=0){
                 Dialog.show("authentification avec succees ","session plein","OK",null);

                  //Session 
                float id = Float.parseFloat(userlistjson.get("id").toString());
                SessionManager.setId((int)id);
                SessionManager.setPassowrd(userlistjson.get("passwd").toString());
                SessionManager.setNom(userlistjson.get("nom").toString());
                SessionManager.setPrenom(userlistjson.get("prenom").toString());

                SessionManager.setEmail(userlistjson.get("email").toString());
                
                //photo 
                
                if(userlistjson.get("photo") != null)
                    SessionManager.setPhoto(userlistjson.get("photo").toString());
                    System.out.println(SessionManager.getEmail());
                }
                else{
                  Dialog.show("Email ou mot de passe incorrect ","veiller les verifier","OK",null);
  
                }
                   
              
                
                
                
                
            }catch(Exception ex) {
               Dialog.show("Champs vide ","saisir votre email et password pour se connecter ","OK",null);

            }
         });
    
         //ba3d execution ta3 requete ely heya url nestanaw response ta3 server.
        NetworkManager.getInstance().addToQueueAndWait(req);
        
    }
    

  
    
    

    
}
