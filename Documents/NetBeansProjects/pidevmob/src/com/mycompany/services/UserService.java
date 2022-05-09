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
import com.codename1.ui.EncodedImage;
import com.codename1.ui.Image;
import com.codename1.ui.TextField;
import com.codename1.ui.URLImage;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.util.Resources;
import com.mycompany.Utils.SessionManager;
import com.mycompany.entities.User;
import com.mycompany.myapp.ProfileForm;
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
    EncodedImage enc;
    Resources theme; 
   
    public static boolean resultOk = true;
    public String b ; 
    Image imgs; 
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
    public void signup(TextField nom,TextField prenom,TextField email,TextField password, ComboBox roles ,TextField country,TextField num,TextField photo ) {
        
     
        boolean valide=false; 
        boolean etat=true; 
        String url = "http://localhost:8000/inscription/"+nom.getText().toString()+"/"+prenom.getText().toString()+"/"+email.getText().toString()+"/"+password.getText().toString()+"/"+roles.getSelectedItem().toString()+"/"+country.getText().toString()+"/"+num.getText().toString()+"/"+photo.getText().toString();
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
            Dialog.show(""+responseData,":D","ok",null );
        }
        );
        
        
        //ba3d execution ta3 requete ely heya url nestanaw response ta3 server.
        NetworkManager.getInstance().addToQueueAndWait(req);
        
            
        
    }
  
    //SignIn
    
    public String signin(TextField email,TextField password ) {
       
         String url = "http://localhost:8000/loginmob/"+email.getText().toString()+"/"+password.getText().toString();
         String urlImage = "http://localhost/loginmob/";
        req = new ConnectionRequest(url,false); 
      
        
        req.addResponseListener((NetworkEvent e) -> {
            JSONParser j=new JSONParser();
            String json = new String(req.getResponseData());
            System.out.println(json);
            
            
            try {
                
                Map<String,Object> userlistjson = j.parseJSON(new CharArrayReader(json.toCharArray()));
               
                
                if(userlistjson.size()!=0){
                  

                  //Session 
                float id = Float.parseFloat(userlistjson.get("id").toString());
                SessionManager.setId((int)id);
                SessionManager.setPassowrd(userlistjson.get("passwd").toString());
                SessionManager.setNom(userlistjson.get("nom").toString());
                SessionManager.setPrenom(userlistjson.get("prenom").toString());
                 SessionManager.setRole(userlistjson.get("role").toString());
                SessionManager.setEmail(userlistjson.get("email").toString());
                    System.out.println(userlistjson.get("photo").toString());
                    b=userlistjson.get("role").toString();
                //photo 
                
                if(userlistjson.get("photo").toString()!= null){
                    SessionManager.setPhoto(userlistjson.get("photo").toString());
                    System.out.println(SessionManager.getEmail());
                     enc= EncodedImage.create("/load.png");
                     System.out.println(userlistjson.get("photo").toString());
                    imgs = URLImage.createToStorage(enc, url,urlImage+userlistjson.get("photo").toString(),URLImage.RESIZE_SCALE_TO_FILL);
                    SessionManager.setImgs(imgs);
                    System.out.println("v"+SessionManager.getImgs());
                }else{
                    imgs= URLImage.createToStorage(enc, url,urlImage+"images.png",URLImage.RESIZE_SCALE_TO_FILL);
                    SessionManager.setImgs(imgs);
                }
                    
                }
            }
                
             catch(Exception ex) {
                ex.getStackTrace();
            }
         });
    
         //ba3d execution ta3 requete ely heya url nestanaw response ta3 server.
        NetworkManager.getInstance().addToQueueAndWait(req);
     return b;
    }
    

  
    
    

    
}
