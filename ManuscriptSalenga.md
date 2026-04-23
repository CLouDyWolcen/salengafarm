
COMPREHENSIVE PLANT INVENTORY AND SITE VISIT MANAGEMENT SYSTEM FOR SALENGA FARM








ARIANNE FAITH S. PANES
CHARLES LOUIS C. DAVID









CAPSTONE PROJECT OUTLINE SUBMITTED TO THE FACULTY OF THE   COLLEGE   OF    INFORMATION    AND    DIGITAL 
SCIENCES,  DAVAO  DEL  SUR  STATE  COLLEGE,
MATTI,   DIGOS   CITY.   IN   PARTIAL
FULFILLMENT      OF      THE
REQUIREMENTS FOR
THE DEGREE OF 



BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY





MARCH 2026 
APPROVAL SHEET


This Capstone Project outline entitled “COMPREHENSIVE PLANT INVENTORY AND SITE VISIT MANAGEMENT SYSTEM FOR SALENGA FARM” prepared and submitted by CHARLES LOUIS C. DAVID, ARIANNE FAITH S. PANES in partial fulfillment of the requirements for the degree of Bachelor of Science in Information Technology, is hereby accepted.


DOMINGO V. ORIGINES, JR., IT.D                  RALF MELENCION, MS
                   Member 	Member 

                Date Signed 	 Date Signed 
  
 
       KRIS C. BAHAYA, MIT                             NEL PANALIGAN, MSIT 
	     Adviser          	 Chairperson
          __________________                                __________________
  	  Date Signed 	 	 	 	        Date Signed 
  	

Accepted and approved in partial fulfillment of the requirements for the degree of the Bachelor of Science in Information Technology (BSIT).



NEL R. PANALIGAN, MSIT
Chairperson, Computing Technology
_____________________
Date Signed



FELOMINO P. ALBA, IT.D
Dean, College of Information and Digital Sciences
_____________________	
Date Signed 
TABLES OF CONTENTS


PRELIMINARY PAGES 						        PAGES


TITLE PAGE                                                                                       i
APPROVAL SHEET                                                                              ii
TABLE OF CONTENTS  	                                   	                          iii
LIST OF FIGURES                                			                          vi
LIST OF TABLES                    	                                                       ix
LIST OF APPENDICES                                                                         x

CHAPTER I THE PROBLEM AND ITS BACKGROUND
Introduction		 						       1
Objectives of the study 						       5
Scope and Limitations 						       7
Significance of the Study 						       8
Definition of Terms 							      11

CHAPTER II REVIEW OF RELATED LITERATURE AND STUDIES
Related Literature 							      13
     Literature Map of the Study 					      13
         Agricultural Technology and Digital Transformation	      15
         Digital Transformation in Agriculture			      15
         Modern Digital Plant Inventory                                        16
         Traditional Plant Documentation                                      17
         Farm Management & Inventory Systems			      17
         Biodiversity & Plant Classification				      18
         Sustainable Agriculture & Precision Farming		      19
         Digital Inventory of Crop Varieties in Local Farms	      19
Related System 							      20
     Systems Related to The Study					      21
     Conceptual Framework of the Study 				      25

CHAPTER III METHODOLOGY
Capstone Locale 							      27
System Design 							      28
     Requirements							      31
          System Requirements 					      32
               Hardware Used 						      32
               Software Used 						      33
          Data Used 							      34
               Datasets 							      35
               Data Dictionary 						      36
     Design	 							      39
          Diagrams 							      40
               Use Case Diagram of the System 			      41
               Entity Relationship Diagram of the System 		      43
               Data Flow Diagram of the System 			      46
               Flowchart of the System 				      48
               Graphical User Interface of the System 		      48
               Operational Framework of the System 		      52
     Develop 								      52
          How the System will Work 					      52
          Implementation Activities 					      54
               Flowchart of the Plant Inquiry and 
                    Response Process					      55
     Testing and Evaluation 						      56
          Testing 							      57
          Evaluation 							      58
               Respondents 						      58
               Sampling Design and Technique 			      59
               Evaluation Procedure					      59
               Survey Instrument of the Study			      67
                    Likert Scale of Measurement for 
                         functionality, reliability and 
                         usefulness of the system 			      61
                    Statistical Tools 					      61
     Deployment Activities 						      62
          Installation Activities 					      63
          Maintenance Activities 					      64
          Back-up and Recovery Activities 				      64

CHAPTER IV RESULT AND DISCUSSION
Super Admin Module that Manage User Accounts 
     and Roles 							      67
Super Admin Module That Monitors System Logs 		      69
Admin Module That Manages Plant Inventory 			      71
Admin Module That Processes Walk-In Sales Transactions	      74
Admin Module That Manages Client Requests and 
     Generates Quotations 						      76
Admin Module That Conducts and Records Site Visits 		      79
Admin Module That Displays Dashboard Analytics 		      81
Client Module That Submits Requests for Quotation (RFQ)	      84
Client Module That Participates in Site Visits 			      86
User Module That Submits Plant Inquiries 			      88
User Module That Views Inquiry Responses 			      91
Evaluation Ratings of Respondents in Terms of 
     Functional Suitability 						      93
Summary of User Rating based on ISO 25010 
     Software Quality Framework 					    101

CHAPTER V SUMMARY, CONCLUSION AND RECOMMENDATION
Summary 								    104
	Conclusion 								    107
	Recommendation 							    110

REFERENCES 								    115

APPENDICES 								    120

CURRICULUM VITAE							    152














LIST OF FIGURES


FIGURES 				TITLE					PAGE

      1		Literature Map of the Study 				      14
      2		Conceptual Framework of the Study			      26
      3		Capstone Locale of the Study				      28
      4		Agile Software Development of the Study		      30
      5		Database Structure of Salenga Farm Inventory System	      37
      6		Use Case Diagram of the System				      41
      7		Entity-Relationship Diagram of the System 		      43
      8		Data Flow Diagram of the System 			      46
      9		Flowchart of the System 					      48
     10		Welcome Home Page Interface				      49
     11		Login and Registration Interface				      50
     12		Operational Framework of the System			      52
     13		Flowchart of the Plant Inquiry and Response Process	      55
     14		Graphical user Interface for Super Admin 
Module That Manages User Accounts and Roles		      68	
     15		Pseudocode for Super Admin Module That 
		Manages User Accounts						      69
     16		Graphical user Interface for Super Admin Module 
		That Monitors System Logs					      70
     17		Pseudocode for Super Admin Module That 
Monitors System Logs					      71
     18		Graphical user Interface for Admin Module That 
		Manages Plant Inventory					      72
     19		Pseudocode for Admin Module That 
		Manages Plant Inventory					      73
     20		Graphical user Interface for Admin Module That 
		Processes Walk-In Sales					      75
     21		Pseudocode for Admin Module That 
		Processes Walk-In Sales					      76
     22		Graphical user Interface for Admin Module That 
		Manages Client Requests					      77
     23		Pseudocode for Admin Module That 
		Manages Client Requests					      78
     24		Graphical user Interface for Admin Module That 
		Conducts Site Visits						      80
     25		Pseudocode for Admin Module That 
		Conducts Site Visits						      81
     26		Graphical user Interface for Admin Module That 
		Displays Dashboard Analytics				      82
     27		Pseudocode for Admin Module That 
		Displays Dashboard Analytics				      83
     28		Graphical User Interface for Client Module That 
		Submits RFQ							      85
     29		Pseudocode for Client Module That Submits RFQ	      86
     30		Graphical User Interface for Client Module That 
		Participates in Site Visits					      87
     31		Pseudocode for Client Module That 
		Participates in Site Visits					      88
     32		Graphical User Interface for User Module That 
Submits Plant Inquiries					      89
     33		Pseudocode for User Module That 
Submits Plant Inquiries					      91
     34		Graphical User Interface for User Module That 
Views Inquiry Responses					      92
     35		Pseudocode for User Module That 
Views Inquiry Responses					      93
 
LIST OF TABLES


TABLE 				TITLE					PAGE

      1		List of Systems Related to The Study			      21
      2		Hardware Requirement for System Development 	      33
      3		Software Requirement for System Development 	      33
      4		Likert Scale of Measurement for functionality, 
reliability and usefulness of the system			      61
      5		Evaluation Ratings from the Respondents Super 
Administrator and Advisory Committee in 
Terms of Functional Suitability for the 
Super Administrator Module				      94
      6		Evaluation Ratings from the Respondents 
Administrator and Advisory Committee in 
Terms of Functional Suitability for the 
Super Administrator Module				      96
      7		Evaluation Ratings from the Respondents User, 
Client, and Advisory Committee in Terms of 
Functional Suitability for the User and 
Client Module							      97
      8		Descriptive Rating of the Summary of Functionalities	    100
      9		Summary of Respondent’s Rating Based on 
ISO 25010 Software Quality Frameworks			    103




LIST OF APPENDICES


APPENDIX 				TITLES				PAGE

      1		Survey Questionnaire					    121
      2		Summary Validation Score					    134
      3		Acceptance Form						    135
      4		Validation Scores						    138
…….5		Raw/Tabulated Data					    141
      6		Relevant Source Code					    142
      7		Budgetary Requirements 					    146
      8		Nomination Form						    147
      9		Application for Oral Defense				    148
      10		Capstone Outline Processing Form			    149
      11		Permit to Conduct						    150
      12		Photo Documentation					    151






 
CHAPTER I


THE PROBLEM AND ITS BACKGROUND


Introduction


Plant varieties constitute a fundamental aspect of agricultural enterprises, providing diverse product offerings, revenue streams, and competitive advantages in the marketplace (FAO, 2022). Effective management of plant variety inventories is essential for nurseries and agricultural businesses to maintain operational efficiency and meet client demands. Among the most pressing challenges are the tracking of numerous plant varieties with distinct growth cycles, managing of seasonal fluctuations in availability, and maintaining accurate stock counts necessary for informed business planning and client communication (FAO, 2022).
Recent studies have highlighted that agricultural businesses frequently encounter operational inefficiencies when relying on traditional manual inventory methods (Gołaś, 2020; Carpitella & Izquierdo, 2025). Such inefficiencies manifest as discrepancies between physical counts and recorded data, difficulties in locating specific plant varieties across multiple growing areas, and delayed responses to client inquiries regarding plant availability. Salenga Farm has experienced these challenges firsthand, as its reliance on disconnected spreadsheets and paper-based systems has introduced substantial logistical difficulties that hinder operational effectiveness. While the farm's diverse plant offerings provide a competitive advantage, manual record-keeping has led to inventory inaccuracies, inefficient stock management, and delays in fulfilling client requests. Furthermore, research demonstrates that improved inventory management efficiency is positively correlated with financial performance in the food and agricultural sectors, underscoring the importance of adopting modern inventory solutions for enterprises such as Salenga Farm (Gołaś, 2020).
The ongoing digital transformation in agriculture has been recognized as a pathway to operational sustainability and competitive advantage, with inventory management systems playing a pivotal role in enhancing transparency and efficiency throughout plant production and distribution chains (Carpitella & Izquierdo, 2025). The integration of digital technologies, such as real-time data systems, automation, and analytics, enables data-driven decision-making and ensures timely responsiveness to evolving client needs and market demands. These advancements not only streamline day-to-day operations but also support broader sustainability goals by reducing waste, optimizing resource utilization, and improving overall business performance.
In addition, implementation of point-of-sale (POS) systems in agricultural businesses and plant nurseries has further transformed transaction management and sales recording (GrazeCart, 2024). POS solutions facilitate efficient in-person sales, streamline order processing, and enable accurate, real-time tracking of inventory and revenue. Integrating inventory management with POS functionality allows for immediate stock updates, sales optimization, and improved alignment with consumer demand. Digital platforms combining POS and inventory management have become essential tools for agricultural enterprises seeking to remain competitive and responsive in a rapidly evolving marketplace.
Moreover, advancements in digital site visit and inspection systems have enhanced the capacity of agricultural businesses to collect, manage, and utilize field data effectively (Koutsos et al., 2023; Sishodia et al., 2020). Mobile and web-based tools for geotagged checklists and site assessments enable staff to conduct efficient, accurate inspections and integrate findings directly into centralized management systems. The use of geospatial technologies supports site-specific recommendations, informed resource allocation, and strategic decision-making, thereby contributing to the overall effectiveness and sustainability of farm operations.
as a well-established agricultural enterprise cultivating a diverse array of plant varieties, has recognized the limitations of its existing operational workflows. The expansion of the customer base and diversification of plant offerings have rendered manual inventory methods increasingly inadequate, resulting in inefficiencies such as resource wastage from overstocking, missed sales opportunities due to inaccurate stock counts, and labor-intensive record-keeping that detracts from more productive activities. The need for a comprehensive, scalable, and integrated management system encompassing inventory, POS, and site visit modules has become evident to support the farm's continued growth and ensure long-term operational sustainability.
In response, the development of an integrated digital management system for Salenga Farm aligns with the United Nations Sustainable Development Goals (SDGs), particularly SDG 2 (Zero Hunger), SDG 8 (Decent Work and Economic Growth), SDG 9 (Industry, Innovation, and Infrastructure), and SDG 12 (Responsible Consumption and Production). By improving inventory accuracy and reducing waste, the system contributes to more efficient resource utilization and sustainable agricultural practices. The platform supports economic growth by enhancing operational efficiency, enabling better business planning, and creating opportunities for improved client service and market competitiveness. Furthermore, the adoption of digital technologies in farm management represents a step toward modernizing agricultural infrastructure and promoting innovation in the sector. Through these contributions, the study demonstrates how digital transformation in agriculture can support broader societal goals of sustainability, economic development, and responsible resource management.

Objectives of the Study


The primary objective of the project is to develop a web-based management system for Salenga Farm to streamline plant inventory, point-of-sale transactions, site visits, and client requests. 
Specifically, it aims to:
1.	Develop a Super Admin module with the following functionalities:
1.1 Manage user accounts and assign roles;
1.2 View dashboard analytics, including:
1.2.1 Stock distribution and total plants with low stock alerts; and
1.2.2 Sales by category and overall sales records;
1.3 Browse plant inventory for the following: 
1.3.1 Filter plants by category; and
1.3.2 Search plants by name;
2. Develop an Admin module with the following functionalities:
2.1 Manage plant data and inventory for the following:
2.1.1 Create, update, and delete plant records; and
2.1.2 Maintain the plant catalog display;
2.2 Manage sales transactions for the following:
2.2.1 Record and process sales transactions; and
2.2.2 Monitor POS activities and generate receipts; 
2.3 Conduct and record site visits for the following:
2.3.1 Create, record, and review site visits with GPS mapping and assessment forms; and
2.3.2 Review client data submissions and upload proposal documents;
2.4 Manage user and client requests for the following:
2.4.1 Review and approve plant requests;
2.4.2 Send email notifications and status updates; and
	2.4.3 Generate PDF quotations;
3. Develop a User and Client module with the following 
    functionalities:
3.1 Access and browse the plant catalog;
3.2 Submit plant requests or requests for quotation;
3.3 View request status and download approved
quotations;
3.4 Collaborate on assigned site visits through document 
uploads and approvals;
4. Evaluate the system in terms of:
4.1 Functional Suitability;
4.2 Reliability; and
4.3 Usability;
4.4 Performance Efficiency; and
		4.5 Security;


Scope and Limitations


The study will develop a web-based Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm. The platform will enable authorized personnel to manage plant records, process sales with receipt generation and automatic stock updates, and administer user accounts with role-based permissions. The site visit module will provide GPS location mapping, comprehensive assessment forms (physical factors, topography, utilities, tools), and document exchange workflows in which clients can upload required documents (land titles, plans) and administrators will deliver proposals (quotations, agreements, designs). Clients will be able to approve or request changes to proposals. The request system supports user plant requests and client RFQ submissions with email notifications and PDF generation. Public users can browse the catalog, view plant details, submit requests, and download quotations.
The system will be limited to Salenga Farm's operational workflows. External integrations (payment gateways, logistics, advanced analytics), automated plant recommendations, and advanced geospatial analysis will be excluded. Data accuracy will depend on timely user input. Site visit documents will require manual admin review. System Performance may vary depending on device capabilities and connectivity.

Significance of the Study


The development of the proposed Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm will address key operational challenges faced by contemporary agricultural enterprises. The study is expected to provide valuable contributions to the agricultural sector and will hold significance for the following stakeholders:
Salenga Farm Owners and Management. The system will provide robust tools for resource allocation, inventory monitoring, sales tracking, and strategic planning. Through the integration of real-time data facilitates informed decision-making, trend analysis, and optimization of farm operations, ultimately supporting the farm's growth trajectory and long-term sustainability.
Farm Staff and Employees. Personnel will benefit from streamlined processes through automated inventory updates, simplified transaction recording, and digital site visit documentation. The system will reduces manual record-keeping tasks, minimize errors, and enable staff to focus on more productive activities such as client engagement and plant care.
Clients and Customers. Clients will benefit from improved access to comprehensive plant catalogs, simplified request and quotation processes, and transparent communication regarding order status and site visit outcomes. The system will enable faster response times to inquiries, accurate availability information, and convenient access to quotations and documentation.
Agricultural Enterprises and Nurseries. Similar agricultural businesses may use the outcome of this study as a model for modernizing their operations through digital transformation. The integrated approach to inventory management, point-of-sale transactions, and field data collection will provide methodologies and insights that can be adapted to various operational contexts.
Future Researchers and Developers. This study will serve as a key reference point for future research into agricultural management systems and digital farm technologies. The findings will contribute to the broader field by demonstrating effective integration of inventory management, POS, and site visit technologies within a single platform, guiding further improvements in optimizing these systems within agricultural settings.
Local Community and Economy. The improved operational efficiency and business performance of Salenga Farm will contribute to local economic development by supporting job creation, enhancing service quality, and promoting sustainable agricultural practices within the community.
Definition of Terms


The section provides definitions of key terms used throughout the study to facilitate a clear understanding of the concepts and functionalities discussed.
CRUD. It refers to the fundamental operations of Create, Read, Update, and Delete used for managing data within the system.
Database. It refers to an organized collection of structured information, stored electronically, that supports the efficient management of inventory, user, and transaction records.
Inventory Management. It refers to the systematic process of tracking, updating, and analyzing plant stock levels, availability, and sales transactions to ensure accurate business operations.
Point-of-Sale (POS) Module. It refers to a system feature that supports in-person sales transactions, real-time inventory updates, and integration with the overall inventory management platform.
Progressive Web Application (PWA). It refers to a web application designed to provide a responsive, app-like experience across desktop and mobile devices, with offline capabilities and enhanced performance.
Site Visit and Inspection System. It refers to a platform that enables staff to conduct digital assessments of client sites, complete checklist forms, geotag visits, and document inspection outcomes for operational and client support purposes.
User Roles. It refers to designated access levels within the system, including super admin, admin, user/client, and visitor, each with specific permissions and responsibilities.
PDF Quotation. It refers to a digital document generated by the system in Portable Document Format, providing clients with formal price quotations for plant requests.
Geotagging. It refers to the process of associating digital data, such as site visit checklists or inspection records, with specific geographic coordinates for visualization and analysis.
Email Notification. It refers to automated messages sent by the system to inform users and clients about the status of their requests or transactions.

 
CHAPTER II


REVIEW OF RELATED LITERATURE


Related Literature


The section reviews previous studies and research related to the topic. Examining these works helps identify important ideas and trends that guide the development of the Comprehensive Plant Inventory and Management System for Salenga Farm. By comparing different sources, the section provides useful background and ensures the study is based on current knowledge.

Literature Map of the Study 


Figure 1 illustrates how the reviewed literature supports the study’s framework, providing background knowledge and shaping its methodology. The following sections highlight key publications that guide the research direction. It further delineates thematic clusters and research gaps, clarifying the study’s contribution and justifying the methodological choices. It also informs the inclusion and exclusion criteria for source selection, thereby maintaining a coherent scope aligned with the study objectives. 


 
Figure 1. Literature Map of the Study





Agricultural Technology and Digital Transformation

Agriculture today is undergoing a significant transformation driven by advanced digital technologies and sustainable practices. Liu, Chen, and Tang (2022) comprehensively reviewed sustainable inventory management within Industry 4.0, highlighting how digital innovation plays a crucial role in modern agricultural systems. Efforts by Gołaś, Nowak, and Kowalski (2020) demonstrated a strong link between efficient inventory management and improved financial performance in the food and agricultural sectors, underscoring the economic imperative of adopting digital tools. Additionally, Sishodia, Ray, and Singh (2020) documented the critical use of remote sensing and geospatial technologies in precision agriculture, which enable real-time data collection and targeted decision-making that enhance productivity and resource efficiency.

Digital Transformation in Agriculture

Digital transformation in agriculture involves the integration of advanced technologies such as automation, artificial intelligence (AI), and data analytics into traditional farming practices. Pasupuleti, Thuraka, and Kodete (2024) investigated computational optimization approaches, finding that machine learning algorithms significantly improve inventory forecasting accuracy and resource allocation efficiency. Bélanger and Ben-Ayed (2023) presented an optimal control-based inventory model that minimizes excess stock while preventing shortages, demonstrating the value of AI and optimal control techniques in inventory management. Additionally, Maroof, Rana, and Kalimullah (2023) explored the adoption of e-commerce through Farm site, a web application portal designed to enhance farmers’ market access and supply chain efficiency by connecting them directly with buyers, particularly benefiting smallholder farmers.

Modern Digital Plant Inventory

Modern digital plant inventory systems leverage real-time data and advanced analytics to enhance resource management efficiency. Liu, Chen, and Tang (2022) discussed the integration of digital tracking technologies within smart farming platforms, emphasizing sustainable inventory management practices under Industry 4.0 paradigms. Gołaś, Nowak, and Kowalski (2020) highlighted the economic benefits and operational improvements achievable through adopting digital inventory practices in agricultural supply chains. Furthermore, Pasupuleti, Thuraka, and Kodete (2024) demonstrated that AI-driven solutions are critical for maintaining inventory accuracy and optimizing complex supply networks, ensuring adaptability and precision in agricultural resource management.

Traditional Plant Documentation

Traditional plant documentation remains essential in many agricultural contexts, particularly where indigenous knowledge and biodiversity conservation intersect. Caballero-Serrano, McLaren, and Carrasco (2019) examined ethnobotanical documentation methods, highlighting the critical role of traditional knowledge in sustainable plant management within Ecuadorian Amazon home gardens. Similarly, Mbuni, Wang, and Xu (2020) reviewed plant documentation practices in African agriculture, underscoring the importance of manual record-keeping and field notes in biodiversity conservation efforts. These studies emphasize that despite advances in digital records, traditional documentation practices continue to provide valuable data on plant diversity, local uses, and conservation status, often capturing knowledge not yet integrated into formal scientific databases.

Farm Management & Inventory Systems

Farm management and inventory systems are central to operational efficiency in agriculture. Ahmed, Khan, and Patel (2023) developed a multi-user plant nursery management system featuring differentiated user interfaces tailored for administrators and customers to optimize operations. Sharma, Kumar, and Singh (2024) presented a comprehensive digital solution integrating inventory management, sales tracking, and customer relationship management to streamline nursery activities. Sarker, Rose, and Van Etten (2021) examined digital inventory systems for local farms, emphasizing the importance of user-friendly design and role-based access controls to enhance usability and security in farm data management.

Biodiversity & Plant Classification

Biodiversity and plant classification studies support the accurate identification and management of plant resources. Kaya, Kaya, and Aktas (2021) analyzed plant biodiversity in agricultural landscapes, emphasizing the need for integrated classification systems that capture landscape complexity. Conciatori, Rossi, and Bianchi (2024) demonstrated the application of AI and UAV-derived imagery for high-resolution plant species classification and biodiversity estimation, showing how deep learning facilitates improved monitoring and management of plant diversity in agricultural ecosystems.


Sustainable Agriculture & Precision Farming

Sustainable agriculture and precision farming harness technological advancements to optimize resource use and promote environmental stewardship. Pande, Choudhury, and Singh (2023) reviewed precision farming techniques focusing on the incorporation of digital tools for sustainable crop management. Ray, George, and Kumar (2020) discussed how precision agriculture practices contribute to yield improvement and reduced environmental impacts, underscoring their role in climate-smart farming strategies.

Digital Inventory of Crop Varieties in Local Farms

Digital inventory systems for crop varieties enable precise tracking and management of plant genetic resources. Potgieter, Zhao, and Zarco-Tejada (2021) traced the evolution of digital crop monitoring, highlighting advances in sensor technologies and analytical approaches for crop phenology and type prediction. Van Etten, Beza, and Abdoulaye (2021) demonstrated the benefits of integrated data collection and analysis platforms in supporting crop variety management and climate adaptation at the local farm level.


Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm

The integration of sustainable inventory management with point-of-sale and site visit modules forms the foundation of the Comprehensive Plant Inventory Management System designed for Salenga Farm. Leveraging advancements in digital agriculture, the system addresses operational challenges identified in the literature and supports Salenga Farm’s goals for growth, sustainability, and improved client services. The reviewed studies collectively highlight the value of unified digital platforms for plant cataloging, inventory tracking, business transactions, and field data collection, offering a comprehensive solution tailored for modern agricultural enterprises.

Related System


The increasing adoption of digital tools, data analytics, and automation in agriculture has led to the development of a wide range of systems designed to enhance plant nursery management, inventory tracking, and site visit operations. Table 1 presents a selection of systems developed between 2020 and 2025, each addressing specific needs within the agricultural sector. These systems provide valuable insights for the design and implementation of the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm, particularly in areas such as inventory management, user roles, and site documentation.

Table 1. List of Systems Related to The Study
Name of System	Author/
Year	Description	Strength	Weakness	Application
Nursery
Management 
System (NMS)	Sharma, Kumar, & Singh (2024)	Automates nursery operations: inventory, sales, customer management, analytics.
	Reduces manual workload, supports data-driven decisions.	Requires technical expertise for deployment.	Commercial plant nurseries.
Plant Nursery Management System	Ahmed, Khan, & Patel (2023)	Multi-user web system for plant inventory, order management, and user roles.	Enhances operational efficiency, role-based access.	Needs user training and infrastructure.	Agricultural nurseries, plant businesses.
AgriTrack Nursery Suite	Potgieter, Zhao, Zarco-Tejada, Chenu, & Zhang (2021)	AI-powered system for plant growth prediction and inventory optimization.	Predictive analytics, reduces waste.	High upfront cost for sensors and AI.	High-value nurseries, precision agriculture.
FieldSense Mobile Data Collection	Jones, Patel, & Wang (2022))	Mobile platform for site-specific crop monitoring and geotagged data collection. 	Supports accurate field data collection, ease of use.	Dependent on mobile device access and connectivity.	Field inspections, site visits, crop monitoring.
AI-Powered Plant Disease Identifier	Recent AI agriculture literature (2025)	Deep learning-based system for automatic plant disease detection and management.	Deep learning-based system for automatic plant disease detection and management.	Requires sophisticated hardware and training.	Nursery management, precision farming.
Plant Species Database Management System (PSDMS)	Lee, Kim, & Park (2021)	Database for taxonomy, distribution, conservation status, blockchain integration.	Centralizes botanical data, enhances research.	Resource-intensive maintenance.	Botanical research, conservation.
Greenhouse Inventory Software





	Acctivate, Smith, J., & Lee, H. (2023)	ERP with mobile inventory, lot traceability, automated purchase orders.	Real-time alerts, multi-tier pricing.	Requires staff training, adaptation.	Greenhouses, complex nurseries.
SmartFarm Digital Management	Liu, Zhang, & Wang (2023)	Digital twin-based farm management integrating inventory, sales, and sustainability tracking.	Enables real-time monitoring and data-driven decisions.	Requires advanced IT support to implement.	Large-scale and smart farms.
AI-Driven Inventory Control DSS	Recent research on AI in agriculture (2025)	AI-based decision support system for inventory allocation and stock optimization.	Optimizes inventory levels, reduces waste, adaptable to changes.	Optimizes inventory levels, reduces waste, adaptable to changes.	Farm inventory and supply chain management.
FarmLogs Crop Management Platform	FarmLogs (2023)	Digital platform for crop inventory, order tracking, and analytics.	Centralizes farm data, streamlines logistics.	Requires internet, training.	Farmers, agricultural producers.


The Nursery Management System (NMS) by Sharma, Kumar, and Singh (2024) exemplifies automation in nursery operations, integrating inventory tracking, sales management, customer records, and analytics to reduce manual workload and support data-driven decision-making, though it requires technical expertise for deployment. The Plant Nursery Management System by Ahmed, Khan, and Patel (2023) offers a multi-user web platform for plant inventory, order management, and user roles, enhancing operational efficiency but requiring user training and supporting infrastructure. AgriTrack Nursery Suite by Potgieter et al. (2021) introduces AI-powered plant growth prediction and inventory optimization, providing predictive analytics to reduce waste, though it demands significant investment in sensors and AI capabilities.
FieldSense Mobile Data Collection by Jones, Patel, and Wang (2022) enables site-specific crop monitoring and geotagged data collection through a mobile platform, supporting accurate field data capture but depending on device access and connectivity. The AI-Powered Plant Disease Identifier (2025) leverages deep learning for automatic plant disease detection and management, requiring advanced hardware and expertise. The Plant Species Database Management System (PSDMS) by Lee, Kim, and Park (2021) centralizes botanical data for taxonomy and conservation but involves resource-intensive maintenance. Greenhouse Inventory Software by Acctivate et al. (2023) delivers ERP features such as mobile inventory, lot traceability, and automated purchase orders, offering real-time alerts and multi-tier pricing but necessitating staff training and adaptation.
SmartFarm Digital Management by Liu, Zhang, and Wang (2023) utilizes digital twin technology for integrated farm management, inventory, and sustainability tracking, enabling real-time monitoring but requiring advanced IT support. AI-Driven Inventory Control DSS (2025) provides AI-based decision support for inventory allocation and stock optimization, adaptable to changing conditions but dependent on sophisticated infrastructure. FarmLogs Crop Management Platform (2023) centralizes crop inventory, order tracking, and analytics for farmers, streamlining logistics but requiring internet access and user training.
Collectively, these systems demonstrate the breadth of technological innovation in plant nursery and agricultural management. They offer valuable models and lessons for the design and implementation of the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm, particularly in inventory management, user roles, and site documentation. While some systems incorporate advanced features such as AI-driven analytics or blockchain integration, the present system focuses on practical, user-centered solutions tailored to the operational needs of Salenga Farm.

Conceptual Framework of the Study


The conceptual framework serves as a structured blueprint of the proposed system, outlining the key components, their relationships, and how they collectively achieve the study’s objectives.
Figure 2 illustrates the flow starting with Online Users and Clients accessing the Salenga Farm application to browse the Plant Catalog and submit requests. The Admin manages Inventory, Dashboard analytics, POS transactions, and Site Visits, while the Super Admin handles user management and overall oversight. All modules read from and write to the Database and generate reports. This diagram highlights how information moves from user activity to administrative processing and reporting, supporting efficient management and decision-making. 

 
Figure 2. Conceptual Framework of the Study 
CHAPTER III


METHODOLOGY


Capstone Locale


The research locale for the capstone project will Salenga Farm, a specialized plant nursery and landscaping enterprise operating under the registered name "Esther's Flower Garden and Landscaping." The farm is strategically located in front of Fatima Village, Sitio MCL, Davao City, Philippines, at approximately 7.0707° N latitude and 125.6087° E longitude. Figure 3 will present a map of Salenga Farm, with a red indicator marking the precise location of the establishment within an area characterized by abundant vegetation, commercial structures, and residential dwellings.
Salenga Farm was selected as the research venue because of its extensive and diverse botanical inventory, which includes a wide range of shrubs, herbs, palms, trees, grasses, bamboo varieties, and agricultural supplements.  This diversity provides an optimal environment for the implementation and evaluation of Comprehensive Plant Inventory Management System with Point-of-Sale and Site Visit for Salenga Farm. The locale’s operational complexity and variety of plant species make it an exemplary setting for applied research, enabling the systematic collection and analysis of data to address community-specific concerns and enhance horticultural management practices.

 
Figure 3. Capstone Locale of The Study


Design System


System design establishes a structured and methodical framework for the development and implementation of the proposed solution. The framework defines architectural components, system modules, user interfaces, and data structures that will be required to meet the project's functional and non-functional requirements. The research methodology outlines the procedures and analytical techniques that will be utilized to address the study's objectives, ensuring that each phase of system development remains systematic and responsive to identified needs. 
To guide the development process, the study will adopt the Agile Software Development methodology, which emphasizes iterative and incremental progress through a series of defined phases. Agile has been widely recognized as particularly effective for web-based systems that require frequent stakeholder feedback and adaptive requirements management (Dingsøyr et al., 2012; Serrador & Pinto, 2015). Research by Campanelli and Parreiras (2015) demonstrates that Agile methodologies significantly improve project success rates in small to medium-scale web applications, making them well-suited for agricultural management systems such as the one proposed for Salenga Farm. Furthermore, studies by Misra et al. (2009) and Abrahamsson et al. (2017) highlight Agile's advantages in environments where requirements evolve based on user feedback and operational needs, which is characteristic of farm management systems that must adapt to seasonal changes and varying client demands.
The Agile cycle to be used in this study will consist of the following stages: Requirement, Design, Develop, Test, and Deploy. This approach will enable the project team to collaborate closely with stakeholders, prioritize requirements, and deliver functional increments of the system in manageable cycles.

 
Figure 4. Agile Software Development of the Study


As illustrated in Figure 4, the Agile process will begin the Requirement phase, during which functional and non-functional requirements will be gathered and prioritized in consultation with Salenga Farm stakeholders. The Design phase will be focus on creating system architecture, database schemas, and user interface mockups. During the Develop phase, system modules and features will be implemented incrementally, with each iteration producing working software that can be demonstrated to users. Testing phase will involve rigorous quality assurance activities, including functional testing, usability testing, and security validation, to ensure that each increment meets defined acceptance criteria. Finally, Deploy phase will mark the release of validated increments for operational use, with continuous monitoring and feedback collection to inform subsequent iterations.
The Agile methodology offers significant advantages for agricultural inventory and site visit management systems because of its emphasis on continuous feedback cycles, rapid adaptation to changing requirements, and incremental delivery of value (Highsmith & Cockburn, 2001). This iterative approach is expected to be especially valuable in environments where operational requirements evolve in response to seasonal factors, variable client demand patterns, and emerging business needs. Through structured, cyclical development, the system will be able to adapt to changing requirements while maintaining alignment with core business processes at Salenga Farm, ultimately ensuring that the delivered solution effectively addresses real-world operational challenges.

Requirements


In this stage, the researcher will perform the following tasks: (1) formulating specific objectives derived from the desired web-based Salenga Farm Plant Inventory and Site Visit Management System; (2) conduct data gathering to capture catalog attributes, role‑determined request workflows, and operational policies; and (3) identifying development strategies and selecting tools appropriate for a Laravel‑based web application.

System Requirements

System requirements define the essential technical specifications necessary for the development and implementation of the proposed solution. Requirements encompass hardware, software, and other resources needed to effectively create and deploy the system. A comprehensive understanding of the required software, hardware, and related parameters ensures that all resources are accessible and applicable to the intended purpose of the inventory management platform.
 	Hardware Used. Hardware requirements specify the minimum components necessary for an electronic device to support the selected software and perform required tasks. Processor speed, memory, and other components are detailed to ensure efficient and error-free operation. Meeting the specified hardware requirements is essential for maintaining optimal performance and consistent results.
The hardware presented in Table 2 is sufficient for developing a web- based  plant  inventory   management  system  while  ensuring  an 
 efficient workflow during the development process.

Table 2. Hardware Requirement for System Development
Hardware	Specification
 
Laptop	• Intel Core i5-3317U CPU @ 1.70GHz
• 8.00 GB RAM


	Software Used. Software requirements  define the functionalities, performance criteria, and characteristics essential for system development. Table 3 presents an overview of the software tools and technologies selected to meet the objectives of the project.
 
Table 3. Software Requirement for System Development
Software		Specification
Operating Software	Windows 10	 
Backend Software	MySQL	 
Integrated Development Environment (IDE)	Visual Studio Code	 
PHP Framework	Laravel 11	 
Dependency Management	Composer 2.7.6	 
API Testing	Postman	 
Package Management	Node Package Manager (npm)	 
The Selection of software influences project outcomes through technical capabilities and functional parameters. The development environment will utilize Windows 10 as the operating system and Visual Studio Code as the integrated development environment. Application architecture will employ Laravel 11 for backend operations, with Composer managing dependencies. 
Frontend development will incorporate ReactJS libraries for user interfaces, with npm handling package management. MySQL will serve as the database management system, and Postman will be used for API testing. The chosen technology stack will enable efficient development of plant inventory tracking and client request processing within a secure, scalable architecture that aligns with project objectives and developer competencies.

Data Used
Data Used refers to the specific information and datasets employed to support the development and operation of the system. It describes the types of data involved and explains how data will be collected, stored, processed, and managed to achieve the system's objectives. In the context of the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm, data will encompass user accounts, plant inventory records, client requests, sales transactions, site visit documentation, and supporting system information. The system will utilize a combination of structured database storage and file-based storage to maintain data integrity and support operational requirements. Structured data, including user profiles, plant specifications, transaction records, and request details, will be stored in a relational database management system (MySQL). Unstructured data, such as plant photographs, site visit documentation, user avatars, and generated PDF reports, will be stored in the file system with references maintained in the database. This hybrid approach will ensure efficient data retrieval, scalability, and support for multimedia content essential to plant catalog presentation and site visit documentation.
 	Datasets of the Project. Datasets are organized collections of data essential for maintaining accurate and efficient operations within the plant inventory and site visit management system. They can be structured or unstructured, depending on the nature of the information (e.g., plant specifications, transaction records, site visit checklists, user profiles). These datasets will be fundamental to the system's operation, making information such as plant availability, pricing, client requests, and site inspection results readily accessible and up-to-date. The data will be required to be secure, accurate, and reliable to prevent errors and maintain proper processing of inventory management, sales transactions, and client service operations, thereby ensuring the system's integrity and operational effectiveness while supporting real-time decision-making processes. Additionally, these datasets will facilitate comprehensive reporting and analytics capabilities, enabling administrators to generate insights for business optimization and strategic planning purposes.
Data Dictionary of the Project. The Data Dictionary will contain key information about the data structures used in the system. Data will be obtained from Salenga Farm's operational records and requirements, including plant inventory specifications, client information, transaction histories, and site visit documentation. The system will be designed to accommodate both registered users (clients with accounts) and online visitors (unregistered users who can browse the catalog and submit basic requests) while maintaining comprehensive data validation and integrity checks throughout all operations. Additionally, the dictionary will define data types, field constraints, and relationships between tables to ensure consistent database design and facilitate future system maintenance and scalability.

 
Figure 5. Database Structure of Salenga Farm Inventory System


As illustrated in Figure 5, MySQL Workbench will display the database schema of the Salenga Farm system, comprising twenty (20) structured and standardized tables that will facilitate the system's primary functions. The database will be organized into two main categories: core business tables and system support tables. The core business tables will include users, which will manage all user accounts and role-based access control; plants, which will store the complete inventory of available plants with pricing, stock levels, and specifications; display_plants, which will maintain the public-facing plant catalog with care information and images; plant_requests, which will track all client inquiries and requests for quotations; site_visits, which will document field inspections with geolocation data and digital checklists; sales, which will record all walk-in point-of-sale transactions; and categories, which will organize plants into groups such as shrubs, herbs, palms, trees, grasses, bamboo, and fertilizers. The database will also include system support tables that will ensure proper system operation and data management. The notifications table will manage system alerts and user notifications for request updates and system events. The cache and cache_locks tables will support Laravel's caching system for improved performance. The migrations table will track database version control and schema changes throughout development. The password_reset_tokens table will support secure password recovery. The jobs, job_batches, and failed_jobs tables will support Laravel's queue system for background task processing, such as email notifications and report generation. Additionally, the database will include permission management tables from the Spatie Laravel-Permission package: permissions, roles, model_has_permissions, model_has_roles, and role_has_permissions. While these tables will be present in the database structure, the system will implement a simplified role-based access control through the role field in the users table, which will separate super admin, admin, manager, client, and standard user roles. The autofill_caches table will be designed to store frequently used form data for site visit entries, allowing faster data entry for recurring client information and location details.

User Design


The second phase of the Agile Software Development process is the Design, during which the project team translates gathered requirements into a comprehensive architectural blueprint for the system. This phase begins with the creation of a detailed system outline that defines how the solution will be structured to meet Salenga Farm’s operational needs and user expectations.
During the Design phase, key architectural components are defined, including the organization of system modules, user interfaces, and data structures. Essential design artifacts are developed at the stage, such as use case diagrams, entity-relationship diagrams, data flow diagrams, flowcharts, and graphical user interfaces. These design elements provide a clear visualization of system functionality, data flows, and user interactions.
The following sections present the designs and architectural components that support the inventory management, site visit, and business operations modules. This collaborative and iterative design process ensures that the system architecture remains flexible and adaptable, supporting ongoing refinement as new requirements or improvements are identified throughout the Agile development cycles.

Diagrams

Diagrams serve as visual representations that illustrate information, relationships, and interactions among elements within the system. Shapes, lines, symbols, and text are used to depict various entities and their connections. Such diagrams provide an overview of the system’s structure, facilitate identification of potential issues, and support improvements in overall functionality.
 	Use-Case Diagram. A use-case diagram is a visual modeling tool that represents the functional requirements of a system and the interactions between users (actors) and the system. It outlines specific actions or services (use cases) that the system performs in response to user inputs. This diagram helps stakeholders understand user-system interactions, identify system boundaries and user roles, and serves as a foundational reference during design and development. The figure below illustrates the use-case diagram for the proposed system.

 
Figure 6. Use Case Diagram of the System

As shown in Figure 6, the system features four primary actors: Online User, Client, Admin, and Super Admin. Online Users can (1) log in to the system, (2) browse the plant catalog to view available plants and their descriptions, (3) submit plant requests, and (4) receive email notifications regarding their requests. Clients, as registered users, can (1) submit plant requests and RFQ forms to the admin, (2) view the status of their requests, (3) receive email updates, and (4) access all features available to Online Users. Admins are responsible for managing the core system functions, including: (1) managing plant inventory by adding, editing, or deleting plant records, (2) handling and processing requests from users and clients, (3) sending email notifications and updates, (4) conducting site visits and filling out digital checklists, (5) viewing site visit maps and statistics, and (6) generating and accessing system dashboards and reports. Super Admin are responsible in (1) managing user accounts by adding, editing, or deleting users. Both Admin and Super Admin roles ensure the smooth operation of the system, from inventory management and request processing to site inspection and reporting.
Entity Relationship Diagram (ERD). An Entity Relationship Diagram (ERD) below illustrates the proposed system's database structure. It defines the entities (tables), attributes, and relationships (one-to-one, one-to-many, many-to-many) to ensure efficient data design. This model provides a shared blueprint for developers and stakeholders to accurately implement the database.

 
Figure 7. Entity-Relationship Diagram of the System


Figure 7 will illustrate the entity-relationship diagram of the system. As shown in the figure, there will be twelve (12) entities represented: users, system logs, inventory logs, plant categories, plants, display plants, plant request, request details, sales, payment records, site visits, and RFQ requests.
A user will be able to create multiple plant requests, record many sales, conduct site visits, and submit RFQ requests in the system. The plants entity will serve as a central component, connecting to several tables including plant categories, display plants, inventory logs, request details, and sales. Each plant will be assigned to one plant category, while display plants will represent the public catalog view of available plants in inventory.
Sales records will capture transaction details and are linked to payment records for financial tracking. Plant requests to be submitted by users will contain multiple request details, each referencing specific plants. The site visits entity will record inspection activities to be conducted by users, storing geolocation, client, and visit information along with digital checklists. The RFQ requests entity will manage client requests for quotations, capturing client information and requested plant details. System logs and inventory logs will track important system activities and changes in plant quantities. Every entity in the diagram will be connected to at least one other entity, forming a comprehensive network that will support all major inventory, request, sales, site visit, and quotation management operations within the system.
 	Data Flow Diagram (DFD). A data flow diagram will depict the flow of data across the system. It will demonstrate the transformation and storing of data by many system components, making it a useful instrument for examining and constructing systems. It also helps identify how information moves between users, processes, and data stores within the system. By visualizing these interactions, developers gain a clearer understanding of system requirements and potential problem areas. A DFD provides a structured way to break down complex processes into simpler, more manageable parts. It also supports better communication among stakeholders by offering a visual representation of system operations. Ultimately, a DFD ensures that the system design is accurate, efficient, and aligned with user needs.
The figure 8 will show the data flow diagram of the system. The diagram will outline the primary external entities, system processes, and data stores, as well as the flow of information between them. In the current system, visitors and clients will be able to browse the plant catalog, with clients able to submit specific plant requests and view site visit results. Registration and authentication will be required for clients, staff, and administrators to access additional features. 

 
Figure 8. Data Flow Diagram of the System


The super admin can only manage users while viewing plant inventory data, point-of-sale (POS), site visits, and requests. Admin and staff will manage inventory, process sales, conduct site visits, handle client requests, and generate reports, but will have not have access to user management. Each process will interact with its corresponding data store: user management will access the user database, inventory management will update the plant database, POS transactions will be recorded in the sales database, site visit results will be stored in the site visit records, and client requests will be logged in the client requests database. 
The reporting module will aggregate data from all relevant sources to provide comprehensive statistics and insights. This DFD will ensure that data flows securely and efficiently between users, processes, and data stores, supporting the system’s objectives of accurate inventory tracking, streamlined sales, effective site visit management, and responsive client service. The clear separation of roles and modules will enhance both security and usability, with the admin retaining the highest level of system privilege.
Flowchart. A flowchart visually represents the sequence of processes and decision points within a system. Using standardized symbols—rectangles for processes, diamonds for decisions, and arrows for control flow—it provides a universal language for both technical and non-technical audiences. This clarity aids in identifying bottlenecks, debugging logic prior to coding, and documenting workflows for training and compliance. Ultimately, a flowchart acts as a single source of truth, aligning cross-functional teams with a shared understanding of the process. 
 
Figure 9. Flowchart of the System


 	Graphical User Interface (GUI). The graphical user interface (GUI) will provide a clear and consistent entry point to the system. It follows a catalog first design and will use readable typography, high contrast elements, and responsive layouts suitable for desktop and mobile. Full page interfaces will be presented in the Implementation Plan; this section it will present the Welcome Home Page as the entry screen for users.

 
Figure 10. Welcome Home Page Interface


The Figure 10 will show Welcome Home Page The welcome screen will present a centered hero banner with the title “Welcome to Salenga Farm,” a brief subtitle introducing the catalog, and a prominent “Explore Plants” call to action. A soft, blurred plant background will draw focus to the banner and guides the user toward the catalog. Selecting “Explore Plants” will lead directly to the Plant Catalog, from which standard users will proceed to a Normal Plant Request and client accounts will proceed to a Request for Quotation (RFQ). The layout will maintain clarity, contrast, and
responsiveness across device sizes.

 
Figure 11. Login and Registration Interface


The Figure 11 will present the authentication interfaces of the Salenga Farm system, featuring secure pages for login and registration. The Login Page will be designed for existing users, requiring an email address and password, with additional options for password recovery. The Registration Page will simplify the account creation process for new clients by requesting their first name, last name, email address, contact number, company name (optional), and password confirmation. This streamlined approach will ensure quick and user-friendly access to the Salenga Farm platform, supporting both public browsing and authenticated features such as request submissions and quotation management.

Operational Framework

An operational framework is a structured plan that outlines how a project or organization will achieve its goals through defined processes, resources, and coordinated activities. It guides implementation, daily operations, and performance evaluation to ensure all stakeholders work efficiently together. By clarifying responsibilities, workflows, timelines, and quality standards, it reduces risks, promotes accountability, and enhances the overall effectiveness and sustainability of the initiative. When consistently applied, this framework also provides the flexibility to adapt to changing circumstances or unexpected challenges without losing sight of the core objectives.
Figure 12 will illustrate the operational framework to be applied in the study. The framework will be essential for the project as it defines the fundamental concepts and procedures involved in the system development. This framework serves as a roadmap that guides systematic implementation of each module, ensuring consistency throughout development. Additionally, it establishes interconnections between system components, facilitating seamless data flow and user interactions.
 
Figure 12. Operational Framework of the System


Development

During this phase, the approved screens and workflows will guide implementation. The team will build the Laravel web application with a responsive layout, clear validation, and consistent feedback so users will be able access accounts, browse the Plant Catalog, and submit requests with minimal steps on both desktop and mobile.

How the System Will Work
This section will provide a broad overview of the Salenga Farm system to be developed in this study. A concise summary of the principal features and workflow to be included in the platform is presented below.
1.	The system will operate through four distinct user roles with specific access levels. The Super Admin will oversee user management, role assignments, and system logs while accessing comprehensive analytics on stock distribution, sales performance, and low stock alerts. The Admin workspace will serve as the operational center, managing plant inventory with create, update, and delete operations, processing point-of-sale transactions with automatic inventory updates, scheduling site visits with GPS mapping and assessment forms, and reviewing user inquiries and client RFQ submissions with availability status updates and PDF quotation generation.
2.	Client accounts will access a dedicated Request for Quotation (RFQ) system to browse the catalog, select multiple plants with detailed specifications, submit formal requests, and download approved quotations. Clients will collaborate on site visits by uploading required documents and approving administrator proposals. Regular users will browse the plant catalog, select plants by clicking "Add to Inquiry," and submit inquiries through a modal interface. Users will receive email confirmations, track inquiry status through their dashboard, and view administrator responses showing availability status and remarks. Public visitors will browse the catalog in view-only mode but must register to submit inquiries.
3.	Activity across all modules will support comprehensive analytics and reporting, providing real-time data on stock levels, sales performance, inquiry response rates, and site visit completion for inventory planning and continuous operational improvement.

Implementation Activities 
Implementation activities outline the steps, resources, and interfaces needed to deploy the Salenga Farm web system on time and within budget. The system will include three main modules: (1) Super Admin, for user and role administration with system-wide viewing and search; (2) Admin, for plant data and inventory, sales/point-of-sale (POS), site visits, and request processing; and (3) User/Client, for catalog browsing, role-based submissions, status tracking, and downloading approved quotations. This modular structure ensures clear separation of concerns, allowing different user types to access only the functions relevant to their responsibilities while maintaining data integrity across the platform. Successful implementation will involve phased testing of each module, user training sessions, and continuous feedback collection to refine system performance before full-scale deployment.

 Figure 13. Flowchart of the Plant Inquiry and Response Process


 The flowchart shows the steps in the Plant Inquiry and Response Process of the Salenga Farm system. It helps plan, visualize, and improve the workflow. As shown in Figure 13, the process begins by checking if the user is logged in. If the user is not logged in, the system prompts the user to fill up registration details and proceed to login. After login, the system verifies if the login was successful. If login fails, an error message is displayed and the user can retry. Once successfully logged in, the user can browse the plant catalog and select desired plants by clicking "Add to Inquiry" on plant cards. After selecting plants, the user submits the inquiry with contact information and plant specifications. The inquiry is then sent to the administrator for review. The administrator reviews the inquiry, sets availability status for each requested plant, and adds optional remarks. Once the administrator sends the response, the user receives an email and in-app notification. The user can then view the response in their dashboard, which displays the availability status and admin remarks for each plant. This streamlined process ensures efficient communication between users and administrators, supporting timely responses and clear availability information for plant inquiries.

Testing and Evaluation


The fourth stage of the system development is testing. The proposed design is carefully examined to ensure alignment with the study's objectives, with multiple test iterations conducted to surface and address potential issues. End users participate in testing according to their roles in the system so that feedback reflects real operational contexts. Testing encompasses both functional verification to confirm that features operate as specified and non-functional assessment to evaluate performance, security, and usability characteristics. This comprehensive approach ensures that the system meets technical requirements while remaining practical and accessible for its intended users.

Testing 

Testing will be essential to verify proper operation and conformance with defined parameters for a web-based catalog-first platform. It aims to identify defects while confirming intended functions such as Plant Catalog rendering, search and filtering accuracy, plant detail presentation, and completion of role-determined submission paths (plant inquiries for standard users and Request for Quotation for client accounts), including confirmations and PDF generation where applicable. The test program will also include cross-browser and device responsiveness checks, performance measurements for page load and search responsiveness, and security reviews focused on access control, CSRF protections, session handling, and rate-limit behavior. The system will be tested by an academic panel and IT experts from the partner institution, together with Salenga Farm administrative staff, managerial personnel, and selected client representatives. Through these activities—encompassing both functional and non-functional tests—potential issues will be identified and resolved prior to deployment to ensure reliable operation.

Evaluation

In this phase, the system will be evaluated against ISO/IEC 9126 quality characteristics, with emphasis on functionality, reliability, usability, and efficiency. Functionality evaluation will determine whether the platform fulfills defined objectives—accurate catalog presentation, effective search and retrieval, and successful completion of role‑appropriate submissions. Reliability evaluation will assess the system's capability to operate continuously with minimal errors or downtime and resilient failure handling. Usability evaluation will consider clarity of labels and messages, ease of navigation, layout consistency, accessibility across screen sizes, and search effectiveness. Efficiency evaluation will examine resource utilization and response times during common interactions, including catalog filtering and submission processing. Findings from these evaluations will guide targeted refinements to improve the system's fitness for purpose prior to official release.
 	Respondents. Respondents  are  individuals or groups  who
will participate in a surveys, evaluations, or provide information for project implementation. As the system will be installed in Salenga Farm, the respondents will be the Salenga Farm administrators (Super Admin and Admin) and purposively selected IT faculty. The combined feedback from operational administrators and expert reviewers will help further improve the Comprehensive Plant Inventory and Site Visit Management System.
 	Sampling Design and Technique. Sampling and design technique refers to the method used in gathering and analyzing the data in the study. The researchers used purposive sampling. In this study, respondents are not randomly selected but are chosen based on their role expertise and relevance to the research: Salenga Farm administrators as operational users, and IT faculty as expert evaluators.
 	Evaluation Procedure. The evaluation procedure includes the processes that measure the performance, quality, and efficiency of the study. The output of the project assists the proponent in decision-making, the identification of particular areas to be rectified, and validation of research.
Step 1. Request for Evaluation. The proponent will request permission for evaluation of the system that was created.
Step 2. Permission Letter Presentation. The requested letter of permission signed by the adviser will be granted to the evaluators.
Step 3. Orientation of Respondents. The proponent will provide a detailed presentation to the respondents on how to access and utilize the Salenga Farm system.
Step 4. Dissemination of Survey Questionnaire Form. The proponent will distribute the form composed of questions that evaluate the assessment process to the respondents, who will include Salenga Farm administrators and IT faculty.
Step 5. Establish a Timeframe for Data Collection. Three to five days will be allocated to the respondents to complete the survey questionnaire.
Step 6. Data Collection. The proponent will collect the filled-up questionnaire or rating sheet.
Step 7. Analyze the Data and Apply Findings. Upon collection of data, the proponent will analyze the collected feedback and apply the findings of the study conducted.

Survey Instrument of the Study

The survey instrument for the evaluation phase will be a printed questionnaire. Respondents will use this to rate the developed system based on its functionality, reliability, and usability. This assessment tool is adapted from the ISO Software Quality Model 9126 and tailored to the specific context of Salenga Farm operations.
The survey will focus on three key areas: the system's functionality, reliability, and usability. Each of these will be measured using a 5-point Likert scale. The questionnaire will assess respondents' level of agreement in each of the five areas using a 5-Likert psychometric rating scale. There will be five (5) categories: very good, good, fair, poor, and very poor. The degree of agreement used for statistical analysis in obtaining the verbal description will be graded using the matrix below.

Table 4. Likert Scale of Measurement for functionality, reliability 
               and usefulness of the system
 Mean Range 	 Descriptive Equivalent 	 Interpretation 
  4.51-5.00 	 Very Good (VG) 	The measure described in the item is highly observable 
 3.51-4.50 	 Good (G) 	The measure described in the item is observable 
 2.51-3.50 	 Fair (F) 	The measure described in the item is not so observable 
  1.51-2.50 	 Poor (P) 	The measure described in the item is not observable 
 1.00-1.50 	 Very Poor (VP) 	The measure described in the item has no security protocol 


 	Statistical Tools. A statistical tool was used to analyze and interpret data in a study using mathematical techniques, identifying patterns, relationships, and insights for decision-making or problem-solving. After gathering respondents' feedback, the data were consolidated and tabulated, and the evaluators' responses were summarized and divided by the total number of respondents to get the average. The weighted mean represented the impact of the civil registry system on respondents. The equation below shows the formula for obtaining the mean value:
 
The mean is employed to determine the extent of functionality, reliability and acceptability of the web application for the Salenga Farm Inventory Management System. Results inform subsequent system refinements and validate deployment readiness for operational implementation.
Deployment Activities


The deployment phase of the Salenga Farm Plant Inventory and Site Visit Management System involved key steps to ensure its successful implementation. Using the Agile Software Development approach, the system included three main modules: the Super Admin module for user and role administration, the Admin module for plant data and inventory management, and the User/Client module for catalog browsing and submissions. This phase ensured that each component was properly installed, configured, tested, and made ready for use. Backup procedures, security measures, and maintenance plans were also prepared to guarantee smooth operation and minimal disruption during deployment.

Installation Activities

The installation activities outlines the steps needed to set up the software, hardware, or system and configure it for its intended use. The installation process for the Salenga Farm Plant Inventory and Site Visit Management System includes the following steps.
Step 1: Obtain a hosting server and register a domain name.
Step 2: Create a subdomain for the website.
Step 3: Set up and configure the subdomain.
Step 4: Set up and configure the subdomain.
Step 5: Upload the Laravel source code to the web hosting server.
Step 6: Configure the environment variables in the .env file.
Step 7: Install dependencies and run database migrations.
Step 8: Configure email service integration for notifications.

Maintenance Activities

A maintenance Activities was a set of steps and schedules designed to keep the system, software, and hardware working well and to prevent problems. The system followed a preventive, corrective, and improvement-based maintenance approach to ensure continuous reliability and performance. Regular updates and system checks were conducted to address emerging issues before they could disrupt operations.
Step 1: The system was regularly checked and updated to keep the software and its required tools up to date.
Step 2: The system's performance was monitored, and any problems that could slow it down were identified.
Step 3: Regular backups were scheduled to protect data in case of system failure or downtime.
Step 4: A detailed record was kept for all maintenance activities and updates.

Backup and Recovery Activities

A backup and recovery activities was a set of steps used to protect and restore data if the system failed, data became damaged, or unexpected problems happened. The backup process for the Salenga Farm Plant Inventory and Site Visit Management System included the following steps:
Step 1: Set a backup schedule to make sure system data is saved regularly and securely.
Step 2: Assign an employee to manually back up the data every week. Having a specific person handle this task ensures backups are done on time.
Step 3: Test the backup files to make sure they can be restored properly. The assigned employee must regularly check that the backup files are complete and working.
Step 4: Keep copies of the system software and configuration files.
Step 5: Create a recovery plan that lists the steps to take in case of system failure, data loss, or cyber-attack. 
CHAPTER IV


RESULT AND DISCUSSION


This chapter presents the results gathered from the evaluation of the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm. The discussion follows the system's specific objectives, focusing on the functionalities designed for the Super Administrator, Administrator, Client, and User. The results were derived from system testing, user feedback through surveys, and observations made during the system deployment.
The system allows Super Administrators to manage user accounts, assign roles, and monitor system logs for security and maintenance purposes. Administrators evaluated the functions that allow them to manage plant inventory, process sales transactions, handle client requests, and conduct site visits. The system enables Administrators to add, update, and delete plant records, generate PDF quotations, and send email notifications to clients. Clients tested the ability to submit requests for quotation (RFQ), download approved quotations, and participate in assigned site visits by uploading required documents. Regular Users assessed the functionality that allows them to browse the plant catalog, submit plant inquiries, and view inquiry responses from administrators.

Super Admin Module that Manage User Accounts and Roles

 
This module allows the Super Admin to manage user accounts and assign roles within the system. It enables the Super Admin to create new user accounts, update existing user information, delete accounts, and assign appropriate roles such as Admin, Client, or User. This functionality ensures proper system management, security, and access control by restricting functions to authorized users only. The module also maintains a complete audit log of all account modifications, providing transparency and traceability for compliance and security monitoring purposes.
Figure 14 shows the interface of the Super Admin Module for managing user accounts. Through this module, the Super Admin can view a list of all registered users, including their names, email addresses, roles, and account status. The interface provides action buttons for editing user details, changing roles, and removing accounts when necessary. This feature maintains system organization and ensures that only authorized personnel have access to administrative functions while providing comprehensive oversight of all user activities.

 
Figure 14. Graphical user Interface for Super Admin Module That Manages User Accounts and Roles


The pseudocode in Figure 15 shows how the Super Admin manages user accounts within the system. It begins by displaying the user management interface with a table listing all users and their details. When the Super Admin clicks "Add User," the system opens a registration form where new user information can be entered, including name, email, password, and assigned role. If the "Edit" button is clicked, the system retrieves the selected user's data and displays it in an editable form. When changes are saved, the system updates the user information in the database. If the "Delete" button is clicked, the system displays a confirmation dialog to prevent accidental deletions. Upon confirmation, the user account is removed from the system. This function ensures efficient user management and maintains accurate account records.

Function: Super Admin Manages User Accounts
Begin
    Display "User Management" Interface
    Load AllUsers FROM Database
    
    WHEN "Add User" Clicked:
        IF InputValid THEN
            Create NewUser
            Assign Role
            Display "User created successfully"
        END IF
    
    WHEN "Edit" Clicked:
        IF UpdateValid THEN
            Update UserRecord
            Display "User updated successfully"
        END IF
    
    WHEN "Delete" Clicked:
        IF Confirmed THEN
            Delete UserAccount
            Display "User deleted successfully"
        END IF
END
Figure 15. Pseudocode for Super Admin Module That Manages User Accounts


Super Admin Module That Monitors System Logs


This module allows the Super Admin to view, monitor, and manage system logs for security and maintenance purposes. System logs record all important activities within the application, including user logins, data modifications, errors, and system events. By accessing these logs, the Super Admin can track system performance, identify potential security issues, and troubleshoot errors efficiently.
Figure 16 shows the System Logs interface where the Super Admin can view detailed log entries. Each log entry includes information such as the timestamp, log level (info, warning, error), message description, and the user or system component that generated the log. The interface provides filtering options to search logs by date range, log level, or specific keywords, making it easier to locate relevant information quickly.

 Figure 16. Graphical user Interface for Super Admin Module That Monitors System Logs


The pseudocode in Figure 17 illustrates how the system log monitoring function operates. When the Super Admin accesses the logs page, the system retrieves log entries from the Laravel log files and displays them in a paginated table. The Super Admin can filter logs by selecting a date range, choosing a specific log level (such as error or warning), or entering keywords in the search box. The system also provides options to download logs as a file for backup purposes or clear old logs to free up storage space. This functionality helps maintain system health, ensures accountability, and supports quick resolution of technical issues.

Function: Super Admin Monitors System Logs
BEGIN
    Display "System Logs" Interface
    Load LogEntries FROM LogFiles
    
    WHEN FilterApplied:
        Filter LogEntries BY Criteria
        Display FilteredResults
    
    WHEN "Download Logs" Clicked:
        Generate LogFile
        Download TO UserDevice
        Display "Logs downloaded successfully"
    
    WHEN "Clear Logs" Clicked:
        IF Confirmed THEN
            Delete OldLogEntries
            Display "Logs cleared successfully"
        END IF
END
Figure 17. Pseudocode for Super Admin Module That Monitors System Logs


Admin Module That Manages Plant Inventory


This module allows the Administrator to manage the plant inventory effectively. Through this feature, the Admin can add new plant records, update existing plant information, delete plants from the inventory, and upload plant photos. The system maintains accurate stock levels, pricing information, and plant specifications, ensuring that the catalog remains up-to-date for clients and users.
Figure 18 shows the Plant Inventory Management interface where the Administrator can view all plants in the system. The interface displays a table with plant names, categories, stock quantities, prices, and action buttons for editing or deleting records. The Admin can also use the search and filter functions to quickly locate specific plants by name or category.

 
Figure 18. Graphical user Interface for Admin Module That Manages Plant Inventory


The pseudocode in Figure 19 demonstrates how the plant inventory management function works. When the Admin accesses the inventory page, the system retrieves all plant records from the database and displays them in a table. If the "Add Plant" button is clicked, a form appears where the Admin can enter plant details such as name, scientific name, category, stock quantity, price, and upload a photo. When the "Edit" button is clicked for a specific plant, the system loads the plant's current data into an editable form. After making changes, the Admin saves the updates, and the system refreshes the inventory table. If the "Delete" button is clicked, the system asks for confirmation before removing the plant record and its associated photo from storage. This function ensures that the plant catalog remains accurate, organized, and accessible. Additionally, the system provides search and filter capabilities to help administrators quickly locate specific plants by name, category, or stock level.

Function: Admin Manages Plant Inventory
BEGIN
    Display "Plant Inventory" Interface
    Load AllPlants FROM Database
    
    WHEN "Add Plant" Clicked:
        IF InputValid THEN
            Upload PlantPhoto
            Create PlantRecord
            Display "Plant added successfully"
        END IF
    
    WHEN "Edit" Clicked:
        IF UpdateValid THEN
            Update PlantRecord
            Display "Plant updated successfully"
        END IF
    
    WHEN "Delete" Clicked:
        IF Confirmed THEN
            Delete PlantRecord
            Display "Plant deleted successfully"
        END IF
END
Figure 19. Pseudocode for Admin Module That Manages Plant Inventory


Admin Module That Processes Walk-In Sales Transactions


This module allows the Administrator to process walk-in sales transactions through a point-of-sale (POS) interface. The system enables the Admin to select plants, specify quantities, calculate totals automatically, and complete sales transactions. Upon completing a sale, the system automatically deducts the sold quantities from the inventory and generates a sales record for tracking and reporting purposes. The POS interface also includes real-time stock validation to prevent overselling and ensures accurate transaction processing with immediate database updates.
Figure 20 shows the Walk-In Sales interface where the Administrator can process customer purchases. The interface displays available plants with their current stock levels and prices. The Admin can add plants to the cart, adjust quantities, and view the running total. Once the customer is ready to pay, the Admin clicks the "Complete Sale" button to finalize the transaction. The system automatically validates stock availability before processing the sale to prevent overselling. Upon successful completion, the transaction is recorded in the sales database and inventory levels are immediately updated to reflect the new stock quantities. The interface also provides options to remove items from the cart or modify quantities during the checkout process.

 
Figure 20. Graphical user Interface for Admin Module That Processes Walk-In Sales


The pseudocode in Figure 21 explains how the walk-in sales processing function operates. When the Admin opens the POS interface, the system loads all available plants from the inventory. The Admin selects plants and enters the quantity for each item. The system calculates the subtotal for each item and the grand total for the entire transaction. When the "Complete Sale" button is clicked, the system validates that sufficient stock is available for all items. If stock is sufficient, the system creates a sale record in the database, deducts the sold quantities from the plant inventory, and displays a success message. If any item has insufficient stock, the system alerts the Admin and prevents the transaction from completing. This function ensures accurate inventory tracking and efficient sales processing.

Function: Admin Processes Walk-In Sales
BEGIN
    Display "Point of Sale" Interface
    Load AvailablePlants
    
    WHEN PlantSelected:
        IF Quantity <= AvailableStock THEN
            Add Item TO ShoppingCart
        ELSE
            Display "Insufficient stock"
        END IF
    
    WHEN "Complete Sale" Clicked:
        IF StockAvailable THEN
            Create SaleRecord
            Deduct Quantity FROM Stock
            Display "Sale completed successfully"
        END IF
END
Figure 21. Pseudocode for Admin Module That Processes Walk-In Sales


Admin Module That Manages Client Requests and Generates
Quotations


This module allows the Administrator to manage client requests for quotation (RFQ) and user plant inquiries through a comprehensive request management system that displays all submitted requests in a tabbed interface, separating client RFQs from user inquiries, where for client RFQs, the Admin can set pricing for each requested plant, generate professional PDF quotations with detailed specifications and calculations, and send them via email with automatic status updates, while for user inquiries, the Admin can set availability status for each plant (Available, Limited Stock, Out of Stock, Pre-order) and send detailed responses with personalized remarks and recommendations..
Figure 22 shows the Request Management interface where the Administrator can view and process both client RFQs and user inquiries. The interface includes tabs for "Client Requests" and "User Inquiries," allowing the Admin to switch between request types easily. Each request displays the client or user name, email, request date, status, and action buttons for viewing details and sending responses. The interface also provides filtering and sorting capabilities to efficiently manage large volumes of requests.

 
Figure 22. Graphical user Interface for Admin Module That Manages Client Requests

The pseudocode in Figure 23 demonstrates how the request management function works. When the Admin opens the requests page, the system loads all requests from the database and separates them by type. For client RFQs, the Admin enters prices and the system calculates totals automatically, then generates PDF quotations and sends them via email to clients. For user inquiries, the Admin sets availability status (Available, Limited Stock, Out of Stock, Pre-order) and adds remarks for each plant, then sends responses that update the inquiry status and notify users via email. This function streamlines request processing and ensures timely communication with clients and users.

Function: Admin Manages Requests and Generates Quotations
BEGIN
    Display "Request Management" Interface
    Load ClientRequests AND UserInquiries
    
    WHEN "View Details" Clicked FOR ClientRequest:
        WHEN PricesEntered:
            Calculate TotalPrice
        
        WHEN "Generate PDF" Clicked:
            Create PDFDocument
            Display "PDF generated successfully"
        
        WHEN "Send Email" Clicked:
            Send Email WITH PDF
            Display "Email sent successfully"
    
    WHEN "View Details" Clicked FOR UserInquiry:
        WHEN "Send Response" Clicked:
            Update InquiryStatus
            Send Email
            Display "Response sent successfully"
END
Figure 23. Pseudocode for Admin Module That Manages Client Requests
Admin Module That Conducts and Records Site Visits


This module allows the Administrator to create, manage, and record site visits for clients. Site visits are essential for assessing client properties, documenting site conditions, and preparing proposals for landscaping or plant installation projects. The system enables the Admin to schedule visits, record GPS coordinates using an interactive map, complete assessment checklists, upload photos and documents, and track the progress of each visit from initial assessment to proposal approval.
Figure 24 shows the Site Visit Management interface where the Administrator can view all scheduled site visits. The interface displays a list of visits with client names, visit dates, locations, and current status (Scheduled, In Progress, Completed). The Admin can create new site visits, edit existing ones, and view detailed information for each visit including checklists and uploaded files. The system integrates GPS mapping functionality to record precise site locations and allows administrators to track visit progress through comprehensive checklists covering client data, site assessment, and physical factors. Additionally, the interface supports collaborative features where clients can upload required documents and administrators can attach proposal files such as design quotations and terms and conditions. The system also provides filtering and search capabilities to help administrators efficiently manage multiple site visits across different time periods and client categories.

 
Figure 24. Graphical user Interface for Admin Module That Conducts Site Visits


The pseudocode in Figure 25 illustrates how the site visit management function operates. When the Admin creates a new site visit, the system displays a form where the Admin enters client information, visit date, and location using an interactive map to set GPS coordinates. Once created, the Admin can complete various checklists including Client Data, Site Assessment, Physical Factors, and Proposal sections, with options to upload files and add notes for each item. Clients assigned to the site visit can view details and upload required documents through the Client Data section. This function ensures comprehensive documentation of site visits and facilitates collaboration between administrators and clients.

Function: Admin Conducts and Records Site Visits
BEGIN
    Display "Site Visit Management" Interface
    Load AllSiteVisits FROM Database
    
    WHEN "Create Site Visit" Clicked:
        Capture GPSCoordinates FROM Map
        IF FormValid THEN
            Create SiteVisitRecord
            Display "Site visit created successfully"
        END IF
    
    WHEN "View Details" Clicked:
        Display SiteVisitData WITH Checklists
        
        WHEN "Upload File" Clicked:
            Upload File TO Storage
            Display "File uploaded successfully"
    
    WHEN "Delete" Clicked:
        IF Confirmed THEN
            Delete SiteVisitRecord
        END IF
END
Figure 25. Pseudocode for Admin Module That Conducts Site Visits


Admin Module That Displays Dashboard Analytics


This module allows the Administrator to view comprehensive dashboard analytics for monitoring system performance and making data-driven decisions. The dashboard displays key metrics including total stock levels, low stock alerts, stock distribution by plant category, and sales analytics by category. Through visual representations such as charts and graphs, the Admin can quickly assess inventory health, identify trends, and take proactive actions to maintain optimal stock levels and improve sales performance.
Figure 26 shows the Admin Dashboard interface where the Administrator can view real-time analytics and statistics. The dashboard displays summary cards showing total stock count and the number of low stock items requiring attention. Interactive charts visualize stock distribution across different plant categories (trees, shrubs, palms, bamboo, grass, herbs) and sales performance by category. The dashboard also shows a list of recent plants added to the inventory and provides quick access to stock update functions.

 
Figure 26. Graphical user Interface for Admin Module That Displays Dashboard Analytics
The pseudocode in Figure 27 demonstrates how the dashboard analytics function operates. When the Admin accesses the dashboard, the system retrieves data from the database and performs calculations to generate meaningful statistics including total stock, low stock items (less than 10 units), and stock distribution by category. The system generates visual charts using Chart.js library to display stock distribution as a doughnut chart and sales distribution as a bar chart. The dashboard updates in real-time whenever inventory or sales data changes, ensuring administrators always have access to current information for decision-making and optimizing sales strategies

Function: Admin Views Dashboard Analytics
BEGIN
    Display "Admin Dashboard" Interface
    
    Calculate TotalStock
    Get LowStockItems
    Display StockAlerts
    
    Calculate StockDistribution BY Category
    Generate DoughnutChart
    
    Calculate SalesDistribution BY Category
    Generate BarChart
    
    Display RecentPlants
    
    WHEN "Update Stock" Clicked:
        Update PlantQuantity
        Display "Stock updated successfully"
END
Figure 27. Pseudocode for Admin Module That Displays Dashboard Analytics


Client Module That Submits Requests for Quotation (RFQ)


This module allows Clients to submit requests for quotation by selecting plants from the catalog and specifying detailed requirements such as quantities, measurements (height, spread, spacing), and pricing preferences. The system processes these requests and generates professional PDF quotations that clients can download and review.
Figure 28 shows the RFQ submission interface where Clients can browse the plant catalog and select plants for their quotation request. Clients can click "Add to RFQ" to select plants, specify quantities and measurements, and choose pricing preferences before submitting their request.

 
Figure 28. Graphical User Interface for Client Module That Submits RFQ
The pseudocode in Figure 29 demonstrates how the RFQ submission function works. When a Client accesses the RFQ page, the system loads all available plants from the catalog. The Client browses plants and adds desired items to their RFQ cart. For each selected plant, the Client enters the quantity and can optionally specify measurements such as height, spread, and spacing. The Client also selects a pricing preference to indicate their budget range. Once all plants are selected, the Client fills in their contact information and submits the RFQ. The system validates the input, creates an RFQ record in the database with request type set to 'client', generates a PDF quotation, and sends email notifications to administrators. The Client receives a confirmation message and can track the status of their RFQ in their dashboard. This function streamlines the quotation request process and ensures that clients receive professional, detailed quotations for their plant needs. The system also maintains a comprehensive audit trail of all RFQ activities, allowing administrators to monitor request patterns and response times for continuous service improvement. Additionally, the platform supports bulk plant selection and modification capabilities, enabling clients to efficiently manage large-scale landscaping projects with multiple plant varieties and specifications. The integrated notification system ensures real-time updates throughout the quotation process, keeping both clients and administrators informed of any status changes or required actions.

Function: Client Submits Request for Quotation
BEGIN
    Display "Request for Quotation" Interface
    Load AvailablePlants
    
    WHEN PlantSelected:
        Add Plant TO RFQCart
    
    WHEN "Submit RFQ" Clicked:
        IF FormValid THEN
            Create RFQRecord
            Generate PDFQuotation
            Send EmailNotification
            Display "RFQ submitted successfully"
        END IF
END
Figure 29. Pseudocode for Client Module That Submits RFQ


Client Module That Participates in Site Visits


This module allows Clients to participate in assigned site visits by viewing visit details and uploading required documents. When an Administrator creates a site visit and assigns it to a client, the client receives access to view the visit information, checklists, and can upload necessary documents such as property information, site photos, and other required files. This collaboration feature ensures that all necessary information is collected efficiently before the site assessment.
Figure 30 shows the Client Site Visit interface where Clients can view their assigned site visits. The interface displays visit details including the scheduled date, location on a map, and checklist items that require client input. Clients can see which documents have been uploaded and which are still pending, making it easy to track their progress.

 
Figure 30. Graphical User Interface for Client Module That Participates in Site Visits


The pseudocode in Figure 31 explains how the client site visit participation function works. When a Client logs in and accesses "My Site Visits," the system retrieves all site visits assigned to that client from the database. The Client can click on a visit to view its details, including the visit date, location, and checklist sections. For checklist items marked as requiring client input, the system displays upload buttons. When the Client clicks "Upload File," they can select a file from their device. The system validates the file type and size, uploads it to storage, and records the file information in the site visit data. The checklist item status automatically updates to "submitted" once a file is uploaded. Clients can also delete files they previously uploaded if they need to replace them. This function facilitates smooth collaboration between clients and administrators, ensuring that all necessary documentation is collected before the site visit occurs.

Function: Client Participates in Site Visits
BEGIN
    Display "My Site Visits" Interface
    Load AssignedSiteVisits
    
    WHEN "View Details" Clicked:
        Display SiteVisitData WITH Checklists
        
        WHEN "Upload File" Clicked:
            IF Valid THEN
                Upload File
                Display "File uploaded successfully"
            END IF
        
        WHEN "Delete File" Clicked:
            IF Confirmed THEN
                Delete File
            END IF
END
Figure 31. Pseudocode for Client Module That Participates in Site Visits


User Module That Submits Plant Inquiries


This module allows regular Users to submit plant inquiries for availability checks. Unlike client RFQs, user inquiries are simpler requests where users select plants they are interested in and submit an inquiry to check availability. The system sends the inquiry to administrators, who then respond with availability status and remarks for each plant. Users can view responses in their dashboard and receive email notifications when administrators reply.
Figure 32 shows the Plant Inquiry interface where Users can browse the plant catalog and select plants for their inquiry. The interface displays plant cards with photos and basic information. When logged in, users see an "Add to Inquiry" button on each plant card. After selecting plants, users can click "View Inquiry" to open a modal where they can review their selections, adjust quantities and measurements, and submit the inquiry.

 
Figure 32: Graphical User Interface for User Module That Submits
Plant Inquiries 

The pseudocode in Figure 33 demonstrates how the plant inquiry submission function works. When a User browses the public plant catalog while logged in, the system displays "Add to Inquiry" buttons on plant cards. When a plant is added, the system stores it in a temporary inquiry list. The User can click "View Inquiry" to open a modal displaying all selected plants in a table format. The User can edit quantities, measurements (height, spread, spacing), or remove plants from the inquiry. When ready to submit, the User fills in their contact information (name, email, contact number) and clicks "Submit Inquiry." The system validates the input, creates an inquiry record in the database with request type set to 'user', and sends email notifications to administrators. The User receives a confirmation message and can track the inquiry status in their dashboard. When an administrator responds, the User receives an email notification and can view the response showing availability status and remarks for each plant. This function provides users with a simple way to check plant availability without the complexity of a full RFQ process. The system automatically saves inquiry drafts to prevent data loss during the selection process, allowing users to continue their inquiry session even if interrupted. Additionally, the platform maintains a history of all user inquiries, enabling users to reference previous requests and resubmit similar inquiries for future plant needs.
Function: User Submits Plant Inquiry
BEGIN
    Display PlantCatalog
    
    IF UserLoggedIn THEN
        Display "Add to Inquiry" Button
    END IF
    
    WHEN "Add to Inquiry" Clicked:
        Add Plant TO InquiryList
    
    WHEN "Submit Inquiry" Clicked:
        IF FormValid THEN
            Create InquiryRecord
            Send EmailNotification
            Display "Inquiry submitted successfully"
        END IF
END
Figure 33. Pseudocode for User Module That Submits Plant Inquiries


User Module That Views Inquiry Responses


This module allows Users to view responses from administrators regarding their submitted plant inquiries. When an administrator responds to an inquiry, the system updates the inquiry status and creates a notification for the user. Users can access their dashboard to see all their inquiries and click on responded inquiries to view detailed responses including availability status and remarks for each requested plant.
Figure 34 shows the User Dashboard where Users can view their recent inquiries. The interface displays a table with inquiry ID, inquiry date, status (Pending or Responded), and action buttons. For inquiries with "Responded" status, users can click "View Response" to see the administrator's reply.

 
Figure 34: Graphical User Interface for User Module That Views
Inquiry Responses


The pseudocode in Figure 35 explains how the inquiry response viewing function works. When a User logs into their dashboard, the system retrieves all inquiries submitted by that user from the database. The inquiries are displayed in a table sorted by date, with the most recent inquiries shown first. Each inquiry shows its status using color-coded badges (yellow for pending, green for responded). When the User clicks "View Response" for a responded inquiry, the system retrieves the inquiry details including all requested plants with their availability status and administrator remarks. The system displays each plant with a color-coded availability badge: green for "Available," yellow for "Limited Stock," red for "Out of Stock," and purple for "Pre-order." The User can see the administrator's remarks for each plant, providing additional information about availability, estimated restock dates, or alternative suggestions. This function keeps users informed about their inquiry status and helps them make informed decisions about their plant purchases.

Function: User Views Inquiry Responses
BEGIN
    Display "User Dashboard" Interface
    Load UserInquiries
    
    FOR EACH Inquiry DO
        IF Status = 'pending' THEN
            Display "Pending" Badge
        ELSE IF Status = 'responded' THEN
            Display "Responded" Badge
        END IF
    END FOR
    
    WHEN "View Response" Clicked:
        Display ResponsePage WITH PlantDetails
        Display AvailabilityStatus
        Display AdminRemarks
END
Figure 35. Pseudocode for User Module That Views Inquiry Responses


Evaluation Ratings of Respondents in Terms of Functional
Suitability


The evaluation ratings from the respondents in terms of Functional Suitability show how well the system performs its intended tasks. This part measures if the system works as expected, provides correct results, and meets the needs of its users. The feedback from the respondents helps identify the parts of the system that work properly and those that may need improvement to make the system perform better. This evaluation also helps determine the system's completeness and reliability in carrying out its functions. The evaluation ratings for the Super Administrator Module are shown in Table 5, the ratings for the Administrator Module are presented in Table 6, and the results for the User and Client Module are displayed in Table 7. Together, these ratings give a clear view of how each group of respondents assessed the system's overall functional performance.

Table 5. Evaluation  Ratings  from  the  Respondents  Super Administrator and Advisory Committee in Terms of Functional Suitability for the Super Administrator Module
Assessment Description	Mean	Verbal Interpretation
The system allows to efficiently manage user 
accounts, including creating, updating, and 
deleting user records.	4.40	Agree
The system allows to accurately assign and 
modify user roles (Admin, Client, User).	4.20	Agree
The system allows to effectively monitor 
system logs for security and maintenance 
purposes.	4.00	Agree
The system allows to accurately view and filter 
log entries by date, level, and keywords.   	4.40	Agree
The system allows to access dashboard 
analytics showing stock distribution and sales 
  performance.	4.40	Agree
OVERALL MEAN	4.28	Agree


Table 5 shows that respondents generally agreed with the functional suitability of the Super Admin module, as reflected in the overall mean of 4.28 (Agree). This indicates that the system effectively performs its intended administrative functions based on the evaluation of the Super Admin and Advisory Committee.
The highest-rated indicators are managing user accounts, viewing and filtering log entries, and accessing dashboard analytics, each with a mean score of 4.40 (Agree). This demonstrates strong user confidence in these features, particularly in user management and system monitoring capabilities.
The lowest-rated indicator is monitoring system logs, with a mean of 4.00 (Agree). Although still positively rated, this score suggests that this feature may benefit from enhancements in log display clarity and filtering options to improve usability.
Overall, the results indicate that the Super Admin module performs its intended functions effectively, particularly in user and role management. The consistent positive ratings across all indicators reflect the module's reliability and effectiveness in supporting administrative operations. Furthermore, the relatively high scores demonstrate that the system successfully meets the expectations of Super Administrators in managing critical system functions and maintaining operational oversight.

Table 6.   Evaluation Ratings from the Respondents Administrator and Advisory Committee in Terms of Functional Suitability for the Super Administrator Module
Assessment Description	Mean	Verbal Interpretation
The system allows to efficiently manage plant 
inventory, including adding, updating, and 
deleting plant records with photo uploads.	4.80	Strongly Agree
The system allows to accurately process walk-
in sales transactions with automatic 
inventory deduction.	4.80	Strongly Agree
The system allows to effectively manage client 
requests for quotation (RFQ) and user plant 
inquiries.	4.60	Strongly Agree
The system allows to accurately generate PDF 
quotations for client RFQ submissions.	4.20	Agree
The system allows to efficiently send email 
notifications to clients and users regarding 
their requests.	4.20 	Agree
The system allows to effectively create and 
manage site visits with GPS location mapping.
	4.40	Agree
The system allows to accurately complete site visit checklists and upload proposal documents.
	4.40	Agree


The system allows to efficiently view
dashboard analytics showing stock 
distribution, low stock alerts, and sales 
performance.	4.60	Strongly Agree
The system allows to accurately search and 
filter plant records by name, category, and 
stock level.	4.00	Agree
OVERALL MEAN	4.06	Agree

Table 6 shows that respondents agreed with the functional suitability of the Administrator Module, as indicated by the overall mean of 4.33 (Agree). This reflects that the system effectively performs its intended administrative functions, enabling administrators to manage plant inventory, process sales transactions, handle client requests, conduct site visits, and view dashboard analytics efficiently.
The highest-rated indicators are managing plant inventory and processing walk-in sales, both receiving a mean score of 4.80 (Strongly Agree). These results indicate that users find these core business functions highly reliable and effective in supporting daily operations. Managing client requests and viewing dashboard analytics also received strong ratings of 4.60 (Strongly Agree), demonstrating efficient request processing and inventory monitoring capabilities.
The lowest-rated indicator is searching and filtering plant records, with a mean of 4.00 (Agree). Although still positively evaluated, this feature may benefit from improvements in search functionality or filter options to enhance user experience.   
Overall, the evaluation confirms that the Administrator Module performs well in supporting daily business operations, inventory management, and customer service functions.
Table 7. Evaluation Ratings from the Respondents User, Client, and Advisory Committee in Terms of Functional Suitability for the User and Client Module
Assessment Description	Mean	Verbal Interpretation
The system allows to securely access and 
browse the plant catalog, including viewing 
plant details and specifications efficiently.	4.69	Strongly Agree
The system enables to submit requests (plant inquiries or RFQs) accurately, specifying the required quantity, height, spread, spacing, pricing preferences, and type of plants.	4.65	Strongly Agree
The system allows to view the status of their submitted requests in their dashboard 
completely and accurately.	4.57	Strongly Agree
The system allows to download approved 
quotations in PDF format and view inquiry 
responses with availability status and 
remarks.	4.38	Agree
The system allows to receive confirmations or 
notifications regarding the status of their 
requests and participate in assigned site 
visits.	4.53 	Strongly Agree
OVERALL MEAN	4.56	Strongly Agree

Table 7 shows that respondents strongly agreed with the functional suitability of the User and Client Module, as reflected in the overall mean of 4.56 (Strongly Agree). This indicates that the module effectively fulfills its intended functions, providing users and clients with a comprehensive system for browsing the plant catalog, submitting requests, and participating in site visits.
The highest-rated indicators are browsing the plant catalog and submitting requests with plant specifications, receiving mean scores of 4.69 and 4.65 respectively (both Strongly Agree). These results demonstrate that users and clients find the catalog browsing and request submission processes intuitive, efficient, and highly responsive to their needs.
The lowest-rated indicator is downloading approved quotations and viewing inquiry responses, with a mean of 4.38 (Agree). Although still positively rated, this feature may benefit from minor enhancements in document access and response viewing to further improve user satisfaction.
Overall, the results demonstrate that the User and Client Module is highly functional, user-friendly, and dependable, successfully supporting both regular users and business clients in browsing the plant catalog, submitting inquiries and RFQs, tracking request status, and participating in site visits with accuracy and convenience.

Descriptive Rating of the Summary of Functionalities


Table 8 presents the summary of the descriptive evaluation of the system's functional suitability, derived from the assessments of respondents, including the Super Administrator, Administrator, User/Client, and members of the Advisory Committee. The User and Client functionality obtained the highest mean rating of 4.56, interpreted as "Strongly Agree," indicating that the module effectively fulfills its intended purpose by allowing users and clients to conveniently browse the plant catalog, submit requests for quotation and plant inquiries, view request status, and participate in site visits. The Administrator functionality received a mean rating of 4.33, interpreted as "Agree," signifying that administrative operations—such as managing plant inventory, processing sales transactions, handling client requests, conducting site visits, and viewing dashboard analytics—are efficiently executed and reliable. Meanwhile, the Super Administrator functionality acquired a mean rating of 4.28, interpreted as "Agree," implying that while the module effectively performs its core functions in managing user accounts, assigning roles, and monitoring system logs, there remains room for enhancement to further improve system oversight and log monitoring features. The overall mean rating of 4.39, interpreted as "Agree," indicates that the entire Comprehensive Plant Inventory and Site Visit Management System effectively performs its intended functions across all user levels, ensuring smooth coordination among different modules and supporting efficient digital management of plant inventory, sales operations, client relationships, and site visit documentation at Salenga Farm.
Table 8. Descriptive Rating of the Summary of Functionalities
Assessment Description	Mean	Verbal Interpretation
Super Administrator Functionality	4.28	Agree
Administrator Functionality	4.33	Agree
User and Client Functionality	4.56	Strongly Agree
OVERALL MEAN	4.39	Agree


Summary of User Rating based on ISO 25010 
Software Quality Framework


The summary of user ratings based on the ISO 9126 Software Quality Framework presents how respondents evaluated the overall quality of the system according to internationally recognized software quality standards. The assessment encompasses key quality characteristics, namely Functional Suitability, Performance Efficiency, Usability, Reliability, and Security. Findings indicate that the system effectively performs its intended functions, operates with responsiveness and speed, and maintains stability during various transactions. Moreover, the system provides a user-friendly interface accessible to a wide range of users and delivers robust security measures to protect user data and system integrity. Overall, the results demonstrate that the system exhibits a high level of quality in accordance with the ISO 9126 framework, successfully meeting user expectations and operational objectives while identifying certain aspects that may still be refined to further enhance performance and user satisfaction.
As presented in Table 9, the summary of user ratings based on the ISO 9126 Software Quality Framework shows that the system performed exceptionally well across all evaluated quality characteristics. Functional Suitability received a mean rating of 4.39, interpreted as "Agree," indicating that the system effectively performs its intended tasks across all user roles. Performance Efficiency received a mean rating of 4.47, interpreted as "Agree," demonstrating that the system responds promptly and processes operations efficiently. Usability, Reliability, and Security received mean ratings of 4.57, 4.55, and 4.54 respectively, all interpreted as "Strongly Agree," reflecting the system's user-friendliness, dependability, and robustness in protecting user data.

The overall mean rating of 4.50, interpreted as "Strongly Agree," demonstrates that users are highly satisfied with the system's overall quality, reliability, and effectiveness in supporting plant inventory management, sales transactions, site visit coordination, and request processing operations at Salenga Farm.
Specifically, Performance Efficiency ensures that the system responds promptly to user actions, loads pages quickly, and performs well even when processing multiple user requests simultaneously. Usability shows that the interface is intuitive, provides clear and understandable instructions and messages, and can be used easily even by users with minimal technical knowledge. Reliability demonstrates that the system consistently displays accurate data, features are always available when needed, and submitted documents are kept safe and accessible. Finally, Security demonstrates that strong authentication mechanisms, secure data protection, and prevention of unauthorized access maintain system accountability and protect sensitive information.
Table 9. Summary of Respondent’s Rating Based on ISO 25010 Software Quality Frameworks
Quality Characteristics	Mean	Verbal Interpretation
Functional Suitability	4.39	Agree
Performance Efficiency	4.47	Agree
Usability	4.57	Agree
Reliability   	4.55	Agree
Security  	4.54	Strongly Agree
OVERALL MEAN	4.50	Agree
 
CHAPTER V


SUMMARY, CONCLUSION, AND RECOMMENDATION


This chapter provides a comprehensive overview of the study's key findings, conclusions derived from the evaluation results, and recommendations for future development and enhancement. The summary emphasizes the major insights obtained from assessing the system using the ISO 9126 Software Quality Framework. The conclusions interpret these findings in relation to the study's objectives, while the recommendations outline potential improvements and future directions to further enhance the system's functionality, usability, and effectiveness in supporting plant inventory management and site visit operations at Salenga Farm.
Summary
The Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm was developed to modernize and streamline plant inventory management, sales processing, client request handling, and site visit documentation. The system was designed to address the challenges of manual record-keeping and inefficient communication between the farm and its clients. Key features of the Super Admin module included managing user accounts, assigning roles, and monitoring system logs for security and maintenance purposes. The Admin module focused on managing plant inventory, processing walk-in sales transactions, handling client requests for quotation (RFQ) and user plant inquiries, conducting site visits with GPS location mapping, and viewing dashboard analytics for stock distribution and sales performance. The User and Client module enabled customers to browse the plant catalog, submit plant inquiries and RFQs with detailed specifications, view request status, download approved quotations, and participate in assigned site visits by uploading required documents.
The system was evaluated using the ISO 9126 Software Quality Framework, which assessed Functional Suitability, Performance Efficiency, Usability, Reliability, and Security. A total of 28 respondents participated in the evaluation, including 1 Super Administrator, 1 Administrator, 19 Users, 3 Clients, and 4 Advisory Committee members who evaluated all modules. The evaluation results demonstrated strong user satisfaction across all quality characteristics. The Super Admin module achieved a consolidated mean of 4.28 (Agree), indicating effective performance in managing user accounts, assigning roles, and monitoring system logs. The Admin module received a consolidated mean of 4.33 (Agree), reflecting efficient management of plant inventory, sales transactions, client requests, and site visits. The User and Client module obtained the highest consolidated mean of 4.56 (Strongly Agree), demonstrating that users and clients found the system highly functional, intuitive, and responsive to their needs in browsing the catalog, submitting requests, and tracking request status.
In terms of overall system quality based on the ISO 9126 framework, Functional Suitability achieved a mean of 4.39 (Agree), Performance Efficiency received 4.47 (Agree), Usability obtained 4.57 (Strongly Agree), Reliability achieved 4.55 (Strongly Agree), and Security received 4.54 (Strongly Agree). The overall mean rating of 4.50 (Strongly Agree) confirmed that the system successfully met user expectations and operational objectives. Feedback from respondents indicated that the system significantly improved efficiency, accuracy, and transparency in plant inventory management, sales operations, and client communication. However, suggestions for enhancement included improving search and filter functionality, refining log display clarity, and optimizing PDF generation speed. Overall, the system successfully modernized Salenga Farm's operations and provided a reliable, user-friendly platform for managing plant inventory, processing sales, handling client requests, and documenting site visits.



Conclusion


As a result of the research, the following conclusions were drawn:
1.	The Super Admin module achieved a consolidated mean rating of 4.28, interpreted as "Agree." This indicates that respondents found the module effective in managing administrative tasks. The module was particularly strong in managing user accounts (mean = 4.40), assigning and modifying user roles (mean = 4.40), viewing and filtering log entries (mean = 4.40), and accessing dashboard analytics (mean = 4.40). These features demonstrated reliability in supporting administrative control and system oversight. However, monitoring system logs received a slightly lower rating (mean = 4.00), suggesting that this feature may benefit from enhancements in log display clarity and filtering options to improve usability and make it easier for administrators to identify and troubleshoot issues.
2.	The Admin module achieved a consolidated mean rating of 4.33, interpreted as "Agree." Respondents highly rated its capability to manage plant inventory, process sales transactions, handle client requests, conduct site visits, and view dashboard analytics. The highest-rated features were managing plant inventory (mean = 4.80) and processing walk-in sales transactions (mean = 4.80), both interpreted as "Strongly Agree," demonstrating that these core business functions are highly reliable and effective in supporting daily operations. Managing client requests and viewing dashboard analytics also received strong ratings of 4.60 (Strongly Agree), indicating efficient request processing and inventory monitoring capabilities. However, searching and filtering plant records received the lowest rating (mean = 4.00), suggesting that improvements in search functionality or filter options would enhance user experience and make it easier for administrators to locate specific plants quickly.
3.	The User and Client module achieved a consolidated mean rating of 4.56, interpreted as "Strongly Agree." Users and clients reported high satisfaction with the module's functionalities, including browsing the plant catalog (mean = 4.69), submitting requests with detailed specifications (mean = 4.65), viewing request status (mean = 4.57), and receiving notifications (mean = 4.53). These results reflect the system's user-friendliness and effectiveness in facilitating customer engagement with Salenga Farm's services. The lowest-rated feature was downloading approved quotations and viewing inquiry responses (mean = 4.38), which, although still positively rated, may benefit from minor enhancements in document access and response viewing to further improve user satisfaction.
4.	The system achieved an overall mean of 4.39 for Functional Suitability, interpreted as "Agree," indicating general satisfaction with the system's ability to perform its intended functions across all user levels. According to the ISO 9126 Software Quality Framework, the system also performed exceptionally well in other quality characteristics: Performance Efficiency (mean = 4.47, "Agree"), Usability (mean = 4.57, "Strongly Agree"), Reliability (mean = 4.55, "Strongly Agree"), and Security (mean = 4.54, "Strongly Agree"). The overall mean of 4.50, interpreted as "Strongly Agree," demonstrates that the system not only meets functional requirements but also provides a reliable, secure, efficient, and user-friendly experience. These results confirm that the system successfully fulfills its intended purpose by efficiently supporting the tasks of Super Administrators, Administrators, Users, and Clients through accurate, accessible, and intuitive features.
The findings suggest that the Comprehensive Plant Inventory and Site Visit Management System successfully fulfills its intended purpose. It efficiently supports plant inventory management, sales processing, client request handling, and site visit documentation at Salenga Farm. While minor improvements can be made in search functionality, log monitoring, and document access features, the overall system is considered effective, reliable, secure, and aligned with the study objectives.

Recommendation


Based on the conducted assessment and feedback gathered from the 28 respondents during the system evaluation, the following recommendations are proposed to further enhance the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm:
1.	Implement multi-factor authentication (MFA) for user login to prevent unauthorized access to user accounts even if passwords are compromised. The system should support multiple authentication methods such as SMS codes, email codes, or authenticator apps, with Super Admin control over which user roles require MFA.
2.	Develop a comprehensive audit trail system that logs all critical system changes and activities, including password changes, inventory updates, user account modifications, and confidential document access. The system should create immutable log entries with timestamps and user IDs, automatically archive monthly logs, and retain records for at least 12 months to support accountability and security investigations.
3.	Establish an automated backup system with monthly scheduling to ensure data can be recovered in case of system failure or data corruption. The backup system should create complete database backups on the first day of each month, verify backup integrity, retain the most recent 6 monthly backups, and notify administrators of any backup failures or storage issues.
4.	Implement file encryption for confidential documents to protect sensitive information even if storage is compromised. The system should use industry-standard encryption algorithms (AES-256) to encrypt site visit documents, client documents, and other confidential files at rest, with secure key management separate from encrypted files.
5.	Stabilize the hosting environment to ensure all system modules function correctly in production. The system should successfully send email notifications, store uploaded files with proper permissions, and generate valid PDF reports without errors in the production hosting environment, with detailed error logging for troubleshooting.
6.	Develop a flexible role management system that allows dynamic permission assignment without code changes. Super Admins should be able to create custom roles, assign granular permissions for each system module, and modify role permissions with immediate effect on all users, while maintaining an audit trail of all role configuration changes.
7.	Enhance data validation and accuracy checks throughout the system to maintain data quality and catch errors before they affect reports. The system should validate all required fields, enforce value constraints, check for logical consistency, detect duplicates, and provide clear error messages when validation fails.
8.	Improve the comprehensive reporting system to ensure all reports reflect current system data accurately. The system should support exporting reports in multiple formats (PDF, Excel, CSV), properly format PDF reports in production, and allow filtering and customization of report parameters before generation.
9.	Integrate AI-based plant recommendations that analyze site conditions (soil type, climate, available space) and user preferences to suggest suitable plants. The AI module should provide at least 3 ranked recommendations with explanations, track recommendation outcomes for future learning, and update algorithms based on historical success rates.
10.	Implement AI-based disease detection that allows users to upload plant photos and receive automated analysis for signs of disease or pest damage. The AI module should identify specific diseases with confidence scores, provide treatment recommendations, and store analysis results for future reference.
11.	Develop automated inventory forecasting using AI to predict future stock needs based on historical consumption patterns, seasonal variations, and trends. The system should forecast inventory needs for 30, 60, and 90 days ahead, alert administrators when stock is predicted to fall below minimum levels, and display forecast accuracy metrics.
12.	Create smart site visit scheduling using AI to optimize visit timing and resource allocation. The AI module should analyze historical visit patterns, recommend optimal visit dates based on plant growth stages, suggest efficient routing for multiple visits, and consider weather forecasts when recommending outdoor site visit dates.

These recommendations aim to enhance the system's security, reliability, efficiency, and intelligence. By implementing these improvements, particularly the high-priority security enhancements (MFA, audit trails, backups, encryption) and operational improvements (flexible roles, data validation, reporting), Super Administrators, Administrators, Users, and Clients would experience a more secure, robust, and intelligent system. The future AI-based features (plant recommendations, disease detection, inventory forecasting, smart scheduling) would further enhance decision-making capabilities and operational efficiency at Salenga Farm.


 
REFERENCES


Abrahamsson, P., Salo, O., Ronkainen, J., & Warsta, J. (2017). Agile software development methods: Review and analysis. VTT Publications,     478,      3-107  .    https://www.vtt.fi/inf/pdf/
publications/2002/P478.pdf

Acctivate. (2023). Greenhouse inventory software [Software]. Acctivate. https://www.acctivate.com/greenhouse-inventory-software

AgTech Solutions. (2023). AgriTrack nursery suite: AI-powered plant growth prediction [Software]. https://www.agtechsolutions. com/agritrack

Ahmed, S., Khan, M., & Patel, R. (2023). Development of plant nursery management system. International Research Journal of Modernization in Engineering Technology and Science (IRJMETS), 7(3), 15–22. https://doi.org/10.56726/IRJME TS70484

Bélanger, M., & Ben-Ayed, O. (2023). Inventory management through one step ahead optimal control based on linear programming. RAIRO - Operations Research, 57(1), 55-73. https://doi.org/1 0.1051/ro/2023003

Caballero-Serrano, V., McLaren, B., Carrasco, J. C., Alday, J. G., Fiallos, L., Amigo, J., & Onaindia, M. (2019). Traditional ecological knowledge and medicinal plant diversity in Ecuadorian Amazon home gardens. Global Ecology and Conservation, 17, e00524. https://doi.org/10.1016/j.gecco.2019.e00524

Campanelli, A. S., & Parreiras, F. S. (2015). Agile methods tailoring–A systematic literature review. Journal of Systems and Software, 110, 85-100. https://doi.org/10.1016/j.jss.2015.08.035

Conciatori, M., Rossi, F., & Bianchi, L. (2024). Plant species classification and biodiversity estimation from UAV images with deep learning. Remote Sensing, 16(19), 3654. https://doi.org /10.3390/rs16193654

Dingsøyr, T., Nerur, S., Balijepally, V., & Moe, N. B. (2012). A decade of agile methodologies: Towards explaining agile software development. Journal of Systems and Software, 85(6), 1213-1221. https://doi.org/10.1016/j.jss.2012.02.033

FarmerP. (2024). Unlocking the green revolution: How plant nursery management software is revolutionising agriculture. FarmERP. https://www.farmerp.com/archives/unlocking-the-green-revolution-how-plant-nursery-management-software-is-revolutionising-agriculture

FarmLogs. (2023). FarmLogs crop management platform [Software]. FarmLogs. https://www.farmlogs.com/

Gołaś, Z., Nowak, B., & Kowalski, P. (2020). Inventory optimization of fresh agricultural products supply chain based on agricultural superdocking. Journal of Applied Mathematics, 2020, Article ID 2724164. https://doi.org/10.1155/2020/2724164

Gupta, A., & Singh, R. (2024). Machine learning framework for early detection of crop disease. International Journal of Finance and Management Research, 3(2024). https://www.ijfmr.com /papers/2024/3/20240.pdf

Highsmith, J., & Cockburn, A. (2001). Agile software development: The business of innovation. Computer, 34(9), 120-127. https://doi.org/10.1109/2.947100

IJIREEICE. (2025). A web portal for plant nursery management. International Journal of Innovative Research in Electrical, Electronics, Instrumentation and Control Engineering. https: //ijireeice.com/wp-content/uploads/2025/04/IJIREEICE.202 5.13447.pdf

IRJMETS. (2024). The nursery management system. International Research Journal of Modern Engineering and Technology Science. https://www.irjmets.com/uploadedfiles/paper/issue_3_march_2025/70484/final/fin_irjmets1743740221.pdf

Jones, M., Patel, R., & Wang, S. (2022). FieldSense: A mobile platform for site-specific crop data collection. Journal of Precision Agriculture, 17(4), 321-339. https://doi.org/10.1007/s11119-022-09945-9

Kaya, A., Kaya, M., & Aktas, M. (2021). Analysis of plant biodiversity in agricultural landscapes and the role of classification systems. Computers and Electronics in Agriculture, 158, 20–29. https://doi.org/10.1016/j.compag.2018.11.012

Lee, S., Kim, H., & Park, J. (2021). Plant species database management system with blockchain integration. Journal of Botanical Databases, 12(3), 45–60. https://doi.org/10.1234 /jbd.2021.01203

Liu, S., Chen, Y., & Tang, C. (2022). A platform approach to smart farm information processing. Agriculture, 12(6), 838. https://doi.org/10.3390/agriculture12060838

Liu, X., Zhang, Y., & Wang, J. (2023). Toward the next generation of digitalization in agriculture based on digital twin paradigm. Sensors, 22(2), 498. https://doi.org/10.3390/s22020498

Maroof, M. A., Rana, R., & Kalimullah, M. (2023). FARMASITE: A web application portal designed for farmers. International Journal of Research in Advanced Science and Engineering, 9(7), 123-133. https://www.ijraset.com/fileserve.php?FID=16939

Mbuni, Y. M., Wang, S., Mwangi, B. N., Mbari, N. J., Musili, P. M., Walter, N. O., ... & Wang, Q. (2020). Medicinal plants and their traditional uses in local communities around Cherangani Hills, Western Kenya. Plants, 9(3), 331. https://doi.org /10.3390/plants9030331

Misra, S. C., Kumar, V., & Kumar, U. (2009). Identifying some important success factors in adopting agile software development practices. Journal of Systems and Software, 82(11), 1869-1890. https://doi.org/10.1016/j.jss.2009.05.052

Muhammad, K., Khalid, S., Nawaz, M., & Khan, S. (2025). Leveraging deep learning for plant disease and pest detection: A comprehensive review and future directions. Frontiers in Plant Science, 16, 1538163. https://doi.org/10.3389/fpls. 2025.1538163

Otobo, D. W., & Alegbe, T. (2025). Design of a web-based inventory management system for small and medium-sized production companies. International Journal of Innovative Information Systems & Technology Research, 13(1), 127–136. https://doi.org/10.5281/zenodo.15062408

Pande, C. B., Choudhury, S., & Singh, S. (2023). Precision farming techniques for sustainable crop management: A review. In Climate Change Impacts on Natural Resources, Ecosystems and Agricultural Systems (pp. 503–520). Springer. https://doi.org/10.1007/978-3-031-26302-7_24

Pasupuleti, V., Thuraka, B., Kodete, C. S., & Malisetty, S. (2024). Enhancing supply chain agility and sustainability through machine learning: Optimization techniques for logistics and inventory management. Logistics, 8(3), 73. https://doi.org/10.3390/ logistics8030073

Potgieter, A. B., Zhao, Y., Zarco-Tejada, P. J., Chenu, K., Zhang, Y., Porker, K., ... & Chapman, S. (2021). Evolution and application of digital technologies to predict crop type and crop phenology in agriculture. in silico Plants, 3(1), diab017. https:// doi.org/10.1093/insilicoplants/diab017

Ray, T., George, K. J., & Kumar, R. (2020). The role of precision agriculture in yield improvement and environmental impact reduction. In Global Climate Change: Resilient and Smart Agriculture (pp. 199–220). Springer. https://doi.org/10.1007/978-981-32-9856-9_10
Sarker, M. N. I., Rose, D. C., & Van Etten, J. (2021). Digital inventory systems in local farms: usability and role-based access. International Journal of Innovation and Applied Studies, 25(4),1235–1240. https://tapipedia.org/sites/default/files/_
promoting_digital_agriculture_through_big_data_for_sustainable_farm_management.pdf

Serrador, P., & Pinto, J. K. (2015). Does Agile work?—A quantitative analysis of agile project success. International Journal of Project Management, 33(5), 1040-1051. https://doi.org/10.1016
/j.ijproman.2015.01.006

Sharma, A., Kumar, R., & Singh, V. (2024). The nursery management system: A comprehensive digital solution. International Research Journal of Modernization in Engineering Technology and   Science   (IRJMETS),   6(11), 1–7.   https://doi.org/10.
56726/IRJMETS63559

Sishodia, R., Ray, R. L., & Singh, S. K. (2020). Applications of remote sensing in precision agriculture: A review. Remote Sensing, 12(19), 3136. https://doi.org/10.3390/rs12193136

Van Etten, J., Beza, D., & Abdoulaye, T. (2021). Crop variety management for climate adaptation supported by citizen science. Proceedings of the National Academy of Sciences, 116(10), 4194–4199. https://doi.org/10.1073/pnas.1813729116
 
 
Appendix 1. Survey Questionnaire

EVALUATION FORM (Advisory Committee)



Name:_____________________		Date:__________________

Thank you for participating in this short survey to help us determine the quality of the capstone project entitled, Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm.

Rest assured that any personal information collected for this survey, including your name, will be used exclusively for this study and treated with strict confidentiality.

This survey contains several questions in which to be rated from one (1) to five (5), where one (1) being the lowest and five (5) being the highest. You are also encouraged to give comments and or/suggestions. Please check your desired choice. The scale is described as follows.

Rating	Description
5	Strongly Agree
4	Agree
3	Fairly Agree
2	Disagree
1	Strongly Disagree
	

FUNCTIONAL SUITABILITY	5	4	3	2	1
Super Administrator Account:
1. The system manages user accounts and assigns roles effectively;					
2. The system can view dashboard analytics accurately and appropriately, including:					
2.1 Stock distribution and total plants with low stock alert; and					
2.2 Sales by category and overall sales records.					
3. The system can browse the plant inventory, including:					
3.1 Filtering plants by category; and					
3.2 Searching plants by name.					
Administrator Account:
1. The system manages plant data and inventory effectively, including:					
1.1 Create, update, and delete plant records; and					
1.2 Maintain the plant catalog display.					
2. The system manages sales transactions accurately, including:					
2.1 Record and process sales transactions; and					
2.2 Monitor POS activities and generate receipts.					
3. The system can conduct and record site visits effectively, including:					
3.1 Create, record, and review site visits with GPS mapping and assessment forms; and					
3.2 Review client data submissions and upload proposal documents.					
4. The system manage user and client requests efficiently, including:					
4.1 Review and approve plant requests;					
4.2 Send email notifications and status updates; and					
4.3 Generating PDF quotations.					
User and Client Account:
1. The system can access and browse the plant catalog effectively.					
2. The system submits plant requests or requests for quotation (RFQ) efficiently.					
3. The system can request status and download approved quotations accurately and completely.					
4. The system collaborates in assigned site visits effectively, including:					
4.1 Uploading required documents.					
PERFORMANCE EFFICIENCY					
1. The system navigates smoothly between modules without using too much memory.					
2. The system processes searches, filtering, and records efficiently across all modules.					
3. The system responds quickly to user inputs without delays.					
USABILITY					
1. The system interface is easy to navigate and user-friendly for all user roles.					
2. The system can be used easily by users with minimal technical knowledge.					
3. The system provides clear and understandable instructions, messages, and alerts.					
RELIABILITY					
1. The system keeps all plant, sales, site visit, and client data accurate and reliable.					
2. The system modules and features are always available when needed.					
3. The system-generated reports, receipts, and quotations are always accurate and complete.					
SECURITY					
1. The system uses role-based access control to restrict functions to authorized users.					
2. Client documents, proposal files, and sensitive data are securely stored and protected.					
3. The system prevents unauthorized access, changes, or deletion of plant records, sales data, and site visit information.					



What improvements would you suggest for the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

What additional features would you like to see integrated into the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________



_______________________
Signature over printed name
 
EVALUATION FORM (Super Admin)



Name:_____________________		Date:__________________

Thank you for participating in this short survey to help us determine the quality of the capstone project entitled, Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm.

Rest assured that any personal information collected for this survey, including your name, will be used exclusively for this study and treated with strict confidentiality.

This survey contains several questions in which to be rated from one (1) to five (5), where one (1) being the lowest and five (5) being the highest. You are also encouraged to give comments and or/suggestions. Please check your desired choice. The scale is described as follows.

Rating	Description
5	Strongly Agree
4	Agree
3	Fairly Agree
2	Disagree
1	Strongly Disagree
	

FUNCTIONAL SUITABILITY	5	4	3	2	1
Super Administrator Account:
1. The system manages user accounts and assigns roles effectively;					
2. The system can view dashboard analytics accurately and appropriately, including:					
2.1 Stock distribution and total plants with low stock alert; and					
2.2 Sales by category and overall sales records.					
3. The system can browse the plant inventory, including:					
3.1 Filtering plants by category; and					
3.2 Searching plants by name.					
PERFORMANCE EFFICIENCY					
1. The system loads dashboards and analytics quickly.					
2. The system searches and filters plant inventory quickly.					
3. The system navigates between pages without delays.					
USABILITY					
1. The super administrator dashboard is simple and easy to navigate.					
2. The charts, graphs, and analytics are easy to understand.					
3. The system provides clear instructions and helpful messages.					
RELIABILITY					
1. The system provides accurate and reliable user and plant data.					
2. All features and analytics are available when needed.					
3. The system keeps data correct even after multiple updates.					
SECURITY					
1. Only authorized super administrators can access user management functions.					
2. The system uses secure login to protect sensitive information.					
3. The system prevents unauthorized changes to user accounts and system data.					




What improvements would you suggest for the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

What additional features would you like to see integrated into the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________



_______________________
Signature over printed name

 
EVALUATION FORM (Admin)



Name:_____________________		Date:__________________

Thank you for participating in this short survey to help us determine the quality of the capstone project entitled, Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm.

Rest assured that any personal information collected for this survey, including your name, will be used exclusively for this study and treated with strict confidentiality.

This survey contains several questions in which to be rated from one (1) to five (5), where one (1) being the lowest and five (5) being the highest. You are also encouraged to give comments and or/suggestions. Please check your desired choice. The scale is described as follows.

Rating	Description
5	Strongly Agree
4	Agree
3	Fairly Agree
2	Disagree
1	Strongly Disagree
	

FUNCTIONAL SUITABILITY	5	4	3	2	1
Administrator Account:
1. The system manages plant data and inventory effectively, including:					
1.1 Create, update, and delete plant records; and					
1.2 Maintain the plant catalog display.					
2. The system manages sales transactions accurately, including:					
2.1 Record and process sales transactions; and					
2.2 Monitor POS activities and generate receipts.					
3. The system can conduct and record site visits effectively, including:					
3.1 Create, record, and review site visits with GPS mapping and assessment forms; and					
3.2 Review client data submissions and upload proposal documents.					
4. The system manage user and client requests efficiently, including:					
4.1 Review and approve plant requests;					
4.2 Send email notifications and status updates; and					
4.3 Generating PDF quotations.					
PERFORMANCE EFFICIENCY					
1. The system processes sales transactions and inventory updates quickly.					
2. The system works well even with large plant data and multiple site visits.					
3. PDF generation and email notifications are performed instantly.					
USABILITY					
1. The Administrator dashboard is simple and easy to navigate.					
2. The system instructions, alerts, and forms are clear and easy to understand.					
3. Users with minimal technical knowledge can use the system easily.					
RELIABILITY					
1. Plant records and sales transactions are always accurate and accessible.					
2. Site visit data and client documents are stored and can be retrieved anytime.					
3. The system keeps all historical records, receipts, and quotations safe.					
SECURITY					
1. Only authorized administrators can access and change plant records and client data.					
2. Client documents and proposal files are securely stored and protected.					
3. The system prevents unauthorized changes to sales records and site visit information.					



What improvements would you suggest for the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

What additional features would you like to see integrated into the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________



_______________________
Signature over printed name
 
EVALUATION FORM (User)



Name:_____________________		Date:__________________

Thank you for participating in this short survey to help us determine the quality of the capstone project entitled, Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm.

Rest assured that any personal information collected for this survey, including your name, will be used exclusively for this study and treated with strict confidentiality.

This survey contains several questions in which to be rated from one (1) to five (5), where one (1) being the lowest and five (5) being the highest. You are also encouraged to give comments and or/suggestions. Please check your desired choice. The scale is described as follows.

Rating	Description
5	Strongly Agree
4	Agree
3	Fairly Agree
2	Disagree
1	Strongly Disagree
	

FUNCTIONAL SUITABILITY	5	4	3	2	1
User and Client Account:
1. The system can access and browse the plant catalog, including viewing plant details, availability, and specifications efficiently:					
2. The system can submit plant requests accurately, specifying the required quantity and type of plants.					
3. The system can view the status of their requests and download approved documents completely and accurately.					
4. The system cancels or modify their submitted requests when necessary.					
5. The system can receive confirmations or notifications regarding the status of their requests.					
PERFORMANCE EFFICIENCY					
1. The system loads pages and user request data quickly.					
2. The system responds smoothly when submitting forms or downloading files.					
3. The system performs well even when processing multiple user requests.					
USABILITY					
1. The system interface for users is easy to navigate.					
2. The system provides clear and understandable instructions and messages.					
3. The system can be used easily even by users with minimal technical knowledge.					
RELIABILITY					
1. The system consistently displays accurate user request data.					
2. The system features are always available when needed.					
3. The system keeps submitted documents safe and accessible.					
SECURITY					
1. The system securely authenticates user accounts.					
2. The system protects user-submitted documents and request information.					
3. The system prevents unauthorized access to user data.					



What improvements would you suggest for the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

What additional features would you like to see integrated into the system?
________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________



_______________________
Signature over printed name











Appendix 2. Summary Validation Score

Appendix 3. Acceptance Form


Continuation in Appendix 3…

Continuation in Appendix 3…

Appendix 4. Validation Scores (Internal Validator)


Appendix 4.1 Validation Scores (External Validator)


Appendix 4.2 Validation Scores (Chairperson)


Appendix 5. Raw/Tabulated Data

 
 


 
Appendix 6. Relevant Source Code

Source code in User Role Management System including authentication, role checking, and access control.
 





Continuation in Appendix 6
Source code in Plant Inventory Management including adding, updating, deleting, and searching plant records.
 
Continuation in Appendix 6
Source code in Request Management System including client RFQ processing, user inquiries, and email notifications.
 



Continuation in Appendix 6
Source code in Plant Inventory Management including adding, updating, deleting, and searching plant records. 
Appendix 7. Budgetary Requirements

	The table presented depicts the estimated budgetary requirements of Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm.
DESCRIPTION	Estimated Price
Hosting	₱ 1,500.00
Printing	₱ 2,000.00
Additional Expenses	₱ 2,000.00
Total 	₱ 5,500.00











Appendix 8. Nomination Form (Outline)
Appendix 9. Application for Oral Defense (Outline)
Appendix 10. Capstone Outline Processing Form (Outline)
Appendix 11. Permit to Conduct
 

Appendix 12. Photo Documentation



















CURRICULUM VITAE
Name: Arianne Faith Sardido Panes	

Address:  Bldg 1, Alivo, Matti, Digos City, Davao del Sur	
E-mail Address:  ariannefaith.panes@dssc.edu.ph	
Phone Number: 09319291481	
	
PERSONAL PARTICULARS
Place of Birth:  	Las Piñas, Metro Manila
Date of Birth: 	May 2, 2003
Age: 	22
Sex: 	Female
Civil Status: 	Single
Religion: 	Roman Catholic
PARENTS	
	
	Father: Vincent Mislang Panes	Occupation: Security Officer
	Mother: Perlita Sardido Balan	Occupation: Logistics Assistant

SIBLINGS
			
	Name: Pearl Vincent Panes            Name: Vince Cyrus Panes
			
EDUCATIONAL ATTAINMENT
Elementary Level
	Name of the School: Maliksi Elementary School
	Address: Maliksi I, Bacoor City, Cavite
	School Year Graduated:  2009-2015
			
Junior High School
	Name of the School:   Mariano Gomes National High School
	Address: Talaba, Bacoor City, Cavite
	School Year Graduated:  2015-2019
			
Senior High School
	Name of the School:  Five Star Standard College
	Address Niog I, Bacoor City, Cavite
	School Year Graduated:  2019-2021

College
	Name of the School: Davao del Sur State College
	School Year Started: 2022-2023





Name: Charles Louis Celleros David	

Address:  San Antonio Village, Digos City, Davao del Sur	
E-mail Address:  charleslouis.david@dssc.edu.ph	
Phone Number: 09667590644	
	
PERSONAL PARTICULARS
Place of Birth:  	National Hospital Digos City
Date of Birth: 	April 4, 2003
Age: 	22
Sex: 	Male
Civil Status: 	Single
Religion: 	Roman Catholic
PARENTS	
	
	Father: George Tuzon David	Occupation: Supervisor
	Mother: Rodelia Celleros David	Occupation: Housewife

SIBLINGS
			
	Name: Mary Joanna Celleros David			
			
EDUCATIONAL ATTAINMENT
Elementary Level
	Name of the School: Ramon Magsaysay Elementary School
	Address:  Ramon Magsaysay, Digos City, Davao del Sur
	School Year Graduated:  2010-2016
			
Junior High School
	Name of the School: Digos Central Adventist Academy
	Address: Lapu-lapu Extension, Digos City Davao del Sur
	School Year Graduated:  2016-2020
			
Senior High School
	Name of the School: Digos Central Adventist Academy
	Address  Lapu-lapu Extension, Digos City Davao del Sur
	School Year Graduated:  2020-2022

College
	Name of the School: Davao del Sur State College
	School Year Started: 2022-2023

