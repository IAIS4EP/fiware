package co.watly.nfcinvoker;

import java.io.BufferedReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.simple.JSONObject;

public class NfcInvoker {

	public static final Pattern PTT_TYPE = Pattern
			.compile("Record type:\\s+(.*)");
	public static final Pattern PTT_URI = Pattern.compile("URI:\\s+(.*)");
	public static final Pattern PTT_TITLE = Pattern.compile("Title:\\s+(.*)");

	public static void main(String[] args) throws Exception {
		if (args.length < 1) {
			System.out.println("specify the proton destination url");
			System.exit(1);
		}
		String destUrl = args[0];
		Process pr;
		BufferedReader in;
		String line;

		String type;
		String uri;
		String title;
		String match;
		while (true) {
			try {
				type = null;
				uri = null;
				title = null;
	
				pr = Runtime.getRuntime().exec("explorenfc-basic");
				in = new BufferedReader(new InputStreamReader(pr.getInputStream()));
				while ((line = in.readLine()) != null) {
					System.out.println(line);
					match = matches(line, PTT_TYPE);
					if (match != null)
						type = match;
					match = matches(line, PTT_URI);
					if (match != null)
						uri = match;
					match = matches(line, PTT_TITLE);
					if (match != null)
						title = match;
				}
				pr.waitFor();
	
				postData(destUrl, type, title, uri);
	
				out.close();
				in.close();
			} catch(Exception e) {
				e.printStackTrace();
			}
		}
	}

	private static String matches(String str, Pattern ptt) {
		Matcher mtc = ptt.matcher(str);
		if (mtc.find()) {
			return mtc.group(1);
		} else {
			return null;
		}
	}

	@SuppressWarnings("unchecked")
	public static void postData(String destUrl, String type, String title, String uri) {
		try {
			DefaultHttpClient httpClient = new DefaultHttpClient();
			HttpPost postRequest = new HttpPost(destUrl);

			JSONObject obj;

			obj = new JSONObject();
			obj.put("Name", "RfidDetected");
			obj.put("Type", type);
			obj.put("Title", title);
			obj.put("Uri", uri);
			System.out.println("Sending event: " + obj.toJSONString());

			String data = obj.toJSONString();

			StringEntity input = new StringEntity(data);
			input.setContentType("application/json");
			postRequest.setEntity(input);

			HttpResponse response = httpClient.execute(postRequest);

			if (response.getStatusLine().getStatusCode() != 200) {
				BufferedReader in = new BufferedReader(new InputStreamReader(
						response.getEntity().getContent()));
				String line = in.readLine();
				while (line != null) {
					System.out.println(line);
					line = in.readLine();
				}
				throw new RuntimeException("Failed : HTTP error code : "
						+ response.getStatusLine().getStatusCode());
			}

			httpClient.getConnectionManager().shutdown();
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}
