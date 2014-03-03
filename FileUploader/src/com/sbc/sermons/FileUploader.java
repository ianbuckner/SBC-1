package com.sbc.sermons;

import javax.swing.*;
import java.awt.*; 
import java.awt.event.*;
import java.io.*;
import java.net.*;


public class FileUploader extends JApplet implements ActionListener, Runnable {
	private static final int CHUNKSIZE = 1024;
	private static final int RETRIES = 10;
	private static final int RETRYSLEEP = 10;

	String rootURL;
	int chunkSize;
	int retries;
	int attempts;
	int retrySleep;
	private static final long serialVersionUID = -866870269342257011L;
	JButton selectButton; 
	JButton uploadButton; 
	JProgressBar progressBar;
	String filename;
	String fn;
	String id;
	boolean uploading = false;
	Thread uploadThread = null;
	Color origBG;
	Color origFG;
	
	public void init() {
		id = getParameter("id");
	    if (id == null) {
	    	// testing value;
	        id = "i1274959759";
	    }
	    
	    try {
	    	chunkSize = Integer.parseInt(getParameter("chunksize"));
	    }
	    catch (NumberFormatException e) {
	    	chunkSize = CHUNKSIZE;
	    }
	    
	    try {
	    	retries = Integer.parseInt(getParameter("retries"));
	    }
	    catch (NumberFormatException e) {
	    	retries = RETRIES;
	    }
	    
	    try {
	    	retrySleep = Integer.parseInt(getParameter("retrySleep"));
	    }
	    catch (NumberFormatException e) {
	    	retrySleep = RETRYSLEEP;
	    }
	    retrySleep = retrySleep*1000; // convert seconds to milliseconds
	    
		try {
		    javax.swing.SwingUtilities.invokeAndWait(new Runnable() {
		        public void run() {
		            createGUI();
		        }
		    });
		} catch (Exception e) {
		    System.err.println("createGUI didn't successfully complete");
		}
		
		rootURL = this.getCodeBase().toString();
		if (rootURL.startsWith("file:/")) {
			// testing in applet viewer
			rootURL = "http://localhost/sermons/mgmt/upload/";
		}
		else {
			rootURL += "/upload/";
		}
	}
	
	private void createGUI() {	    
		JPanel panel = new JPanel();
		panel.setLayout(new BoxLayout(panel, BoxLayout.PAGE_AXIS));
		
	    selectButton = new JButton("Select File");
	    uploadButton = new JButton("Upload");
	    selectButton.addActionListener(this);
	    uploadButton.addActionListener(this); 
	    uploadButton.setEnabled(false);
	    progressBar = new JProgressBar();
	    	    
	    selectButton.setAlignmentX(Component.CENTER_ALIGNMENT);
	    uploadButton.setAlignmentX(Component.CENTER_ALIGNMENT);
	    progressBar.setAlignmentX(Component.CENTER_ALIGNMENT);

	    panel.add(selectButton);
	    panel.add(Box.createRigidArea(new Dimension(0, 5)));
	    panel.add(uploadButton);
	    panel.add(Box.createRigidArea(new Dimension(0, 5)));
	    panel.add(progressBar);    	    
	    panel.add(Box.createRigidArea(new Dimension(0, 5)));
	
	    Container c = getContentPane();
	    c.setLayout(new BorderLayout());
	    c.setBackground(new Color(200, 198, 183));
	    panel.setBackground(new Color(200, 198, 183));
	    c.add(panel, BorderLayout.CENTER);
	    
	    selectButton.setForeground(new Color(60, 59, 53));

	    progressBar.setForeground(new Color(146,17,126));
	    progressBar.setBackground(new Color(200, 198, 183));
	    progressBar.setBorder(BorderFactory.createLineBorder(new Color(161, 149, 131), 1));
	
	    origBG = uploadButton.getBackground();
	    origFG = uploadButton.getForeground();
	}

	
	public void actionPerformed(ActionEvent evt)  
    { 
		if (evt.getSource() == selectButton) {
			JFileChooser fC = new JFileChooser();
			ExtensionFileFilter jpegFilter = new ExtensionFileFilter("MP3 Files", new String[] {"mp3"});

		    fC.setFileFilter(jpegFilter);
			fC.setFileSelectionMode(JFileChooser.FILES_ONLY);

			int returnVal = fC.showOpenDialog(this);
			if (returnVal == JFileChooser.APPROVE_OPTION) {
		        File file = fC.getSelectedFile();
		        
		    	filename = file.getAbsolutePath();
		    	fn = file.getName().replace(" ", "");
		    	uploadButton.setEnabled(true);
		    	uploadButton.setBackground(new Color(146,17,126));
		    	uploadButton.setForeground(new Color(200, 198, 183));
		    }
			return;
		}
		if (evt.getSource() == uploadButton) {
			if (uploading) {
				uploading = false;
				try {
					uploadThread.join();
				}
				catch (Exception e) {}
				uploadThread = null;
				selectButton.setEnabled(true);
				uploadButton.setEnabled(false);
				uploadButton.setText("Upload");
			}
			else {
				uploading = true;
				selectButton.setEnabled(false);
				uploadButton.setText("Stop");
				uploadButton.setBackground(origBG);
		    	uploadButton.setForeground(origFG);
				uploadThread = new Thread(this);
				uploadThread.start();				
			}
			return;
		}
    }

	@Override
	public void run() {
		long fileSize = new File(filename).length();	
		int sizeOnServer = getUploadedSize();
	
		progressBar.setValue(0);

		if (sizeOnServer != -1) {
			if (sizeOnServer < fileSize) {
				progressBar.setMaximum((int)fileSize);
				progressBar.setValue(sizeOnServer);
				
				while ((attempts < retries) && (sizeOnServer < fileSize) && (uploading)) {
					int bSent = uploadChunk(sizeOnServer);
					if (bSent > 0) {
						attempts = 0;
						sizeOnServer += bSent;
						progressBar.setValue(sizeOnServer);
					}
					else {
						attempts++;
						try {	
							Thread.sleep(retrySleep);
						} catch (InterruptedException e) {}
					}
				}
				if (!uploading) {
					return;
				}
			}
			if (sizeOnServer == fileSize) {
				boolean res = false;
				attempts = 0;
				while ((attempts < retries) && (res == false)) {
					res = doCompletion();
					if (!res) {
						attempts++;
						try {	
							Thread.sleep(retrySleep);
						} catch (InterruptedException e) {}
					}
				}
				if (res) {
					try {
						String s = this.getDocumentBase().toString();
						
						s += "index.php";
						this.getAppletContext().showDocument(new URL(s));
					}
					catch (Exception e) {}
				}
			}
		}
		else {
			System.err.println("Failed to contact server, try again.");
		}
		selectButton.setEnabled(true);
		uploadButton.setEnabled(false);
		uploadButton.setText("Upload");
	}
	
	protected int getUploadedSize() {
		int size = -1;
		
		String u = rootURL + "upload.php?op=size&id=" + id + "&name=" + fn;
		
		try
		{
			URL url = new URL(u);
			URLConnection conn = url.openConnection();
			BufferedReader rd = new BufferedReader(new InputStreamReader(conn.getInputStream()));

			String line = rd.readLine();
			rd.close();
			
			size = Integer.parseInt(line);
		} catch (Exception e) {
			e.printStackTrace();
		}	
		return size;
	}
	
	protected int uploadChunk(int startPoint) {
		int res = -1;
		File f = new File(filename);
		
		try {
			FileInputStream fileinputstream = new FileInputStream(f);
	        byte bytearray[] = new byte[chunkSize];
	
	        fileinputstream.skip(startPoint);
	        int bRead = fileinputstream.read(bytearray);
	
	        if (bRead > 0) {
	    		String url = rootURL + "upload.php?op=upload&id=" + id + "&name=" + fn;
	    		URL u = new URL(url);
	            URLConnection c = u.openConnection();

	            c.setDoOutput(true);
	            if (c instanceof HttpURLConnection) {
	                    ((HttpURLConnection)c).setRequestMethod("POST");
	            }

	            c.getOutputStream().write(bytearray, 0, bRead);
	            c.getOutputStream().close();
	            
	            BufferedReader rd = new BufferedReader(new InputStreamReader(c.getInputStream()));

				String line = rd.readLine();
				rd.close();				

	        }
	        res = bRead;
	        fileinputstream.close();
		}
		catch (Exception e) {
			System.err.println(e);		
		}
         
		return res;
	}
	
	boolean doCompletion() {
		boolean res = false;
		
		String u = rootURL + "upload.php?op=done&id=" + id + "&name=" + fn;
		
		try
		{
			URL url = new URL(u);
			URLConnection conn = url.openConnection();
			BufferedReader rd = new BufferedReader(new InputStreamReader(conn.getInputStream()));

			String line = rd.readLine();
			rd.close();
						
			res = (line.compareToIgnoreCase("done") == 0);
		} catch (Exception e) {
			e.printStackTrace();
		}	
		
		return res;
	}
}
	