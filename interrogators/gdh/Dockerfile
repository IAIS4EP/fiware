FROM jetty:9.3.2

# svn export URL/gdh_apitest.jar

RUN echo "probe GE GDH for compliance."
# will run API compliance test against another Docker VM running this GE (this endpoint needs to be known in this context)
RUN java -jar gdh_gett.jar [params]

