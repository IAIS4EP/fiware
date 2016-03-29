package co.watly.server.rest.events;

import java.util.Date;

import javax.xml.bind.annotation.XmlRootElement;

import com.fasterxml.jackson.annotation.JsonSetter;

@XmlRootElement
public class MultipleRfidDetected extends Event {

	private String type;
	private String title;
	private String uri;
	private String chronon;
	private Date occurrenceTime;
	private Date expirationTime;
	
	public String getType() {
		return type;
	}
	@JsonSetter("Type")
	public void setType(String type) {
		this.type = type;
	}
	public String getTitle() {
		return title;
	}
	@JsonSetter("Title")
	public void setTitle(String title) {
		this.title = title;
	}
	public String getUri() {
		return uri;
	}
	@JsonSetter("Uri")
	public void setUri(String uri) {
		this.uri = uri;
	}
	
	public String getChronon() {
		return chronon;
	}
	@JsonSetter("Chronon")
	public void setChronon(String chronon) {
		this.chronon = chronon;
	}
	
	public Date getOccurrenceTime() {
		return occurrenceTime;
	}
	@JsonSetter("OccurrenceTime")
	public void setOccurrenceTime(Date occurrenceTime) {
		this.occurrenceTime = occurrenceTime;
	}
	
	public Date getExpirationTime() {
		return expirationTime;
	}
	@JsonSetter("ExpirationTime")
	public void setExpirationTime(Date expirationTime) {
		this.expirationTime = expirationTime;
	}
	
}

//{"Certainty":"0.0","Cost":"0.0","Name":"TrafficReport","EventSource":"","Annotation":"","Duration":"0.0",
//"volume":"4000","EventId":"744d6a23-3feb-411c-936a-1bf53ea5e95f","DetectionTime":"18\/02\/2016-15:06:03"}

//{"Certainty":"0.0","Cost":"10.0","Name":"ExpectedCrash","EventSource":"","OccurrenceTime":"19\/02\/2016-10:41:44",
//"Annotation":"","Duration":"0.0","EventId":"4a36e056-408d-439f-87c8-ff720b6cce38","DetectionTime":"19\/02\/2016-10:41:44"}