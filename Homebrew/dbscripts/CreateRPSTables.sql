USE [RPSAnnounceDB]
GO

/****** Object:  Table [dbo].[AnnouncementTbl]    Script Date: 03/28/2010 10:02:51 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE LOGIN [rps_access] WITH PASSWORD='rp5acc3$$', DEFAULT_DATABASE=[RPSAnnounceDB], DEFAULT_LANGUAGE=[us_english], CHECK_EXPIRATION=ON, CHECK_POLICY=ON
GO

CREATE USER [rps_access] FOR LOGIN [rps_access] WITH DEFAULT_SCHEMA=[dbo]
GO

EXEC sp_addrolemember db_datareader, [rps_access]
GO

EXEC sp_addrolemember db_datawriter, [rps_access]
GO

CREATE TABLE [dbo].[AnnouncementTbl](
	[DoctorName] [varchar](50) NOT NULL,
	[PatientList] [varchar](max) NOT NULL,
	[NoteList] [varchar](max) NOT NULL,
	[SortOrder] [int] NOT NULL
) ON [PRIMARY]

GO
CREATE TABLE [dbo].[FilterTbl](
	[UserName] [varchar](50) NOT NULL,
	[Show] [varchar](50) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [dbo].[UserTbl](
	[UserName] [varchar](50) NOT NULL,
	[Edit] [bit] NOT NULL,
	[Admin] [bit] NOT NULL
) ON [PRIMARY]

GO

CREATE TABLE [dbo].[SettingsTbl](
	[RefreshRateSecs] [int] NOT NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO
